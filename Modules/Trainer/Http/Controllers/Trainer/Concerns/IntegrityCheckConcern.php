<?php

namespace Modules\Trainer\Http\Controllers\Trainer\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentAnswer;
use Modules\Assignment\Entities\AssignmentSubmission;

trait IntegrityCheckConcern
{
    private function runSubmissionIntegrityCheck($checkType, $text, AssignmentSubmission $submission)
    {
        $labels = [
            'ai_detection' => 'AI Detection',
            'paraphraser' => 'Paraphraser Checking',
            'plagiarism' => 'Plagiarism Checking',
        ];

        $external = $this->callConfiguredIntegrityProvider($checkType, $text, $submission);
        if ($external) {
            return $external;
        }

        $words = str_word_count(strtolower($text), 1);
        $wordCount = count($words);
        $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $sentenceCount = max(count($sentences), 1);
        $avgSentenceLength = round($wordCount / $sentenceCount, 1);
        $uniqueWords = count(array_unique($words));
        $lexicalDiversity = $wordCount > 0 ? round(($uniqueWords / $wordCount) * 100, 1) : 0;
        $repeatedPhraseCount = $this->countRepeatedPhrases($text);
        $sentenceVariance = $this->sentenceLengthVariance($sentences);
        $transitionRate = $this->transitionPhraseRate($text, $wordCount);
        $sources = [];

        if ($checkType === 'ai_detection') {
            $score = min(100, max(0, (int) round(
                ($sentenceVariance < 8 ? 30 : 12)
                + ($lexicalDiversity < 42 ? 25 : 8)
                + ($transitionRate > 3.5 ? 20 : 6)
                + ($avgSentenceLength > 24 ? 15 : 5)
            )));
            $metrics = [
                'Words checked' => $wordCount,
                'Average sentence length' => $avgSentenceLength,
                'Sentence length variance' => $sentenceVariance,
                'Lexical diversity' => $lexicalDiversity . '%',
                'AI-style transition phrase rate' => $transitionRate . '%',
            ];
            $provider = 'Local AI-writing indicators';
        } elseif ($checkType === 'paraphraser') {
            $longSentenceRate = $this->longSentenceRate($sentences);
            $score = min(100, max(0, (int) round(
                ($longSentenceRate > 45 ? 30 : 12)
                + ($sentenceVariance > 18 ? 22 : 8)
                + ($lexicalDiversity > 55 ? 18 : 8)
                + min($repeatedPhraseCount * 5, 20)
            )));
            $metrics = [
                'Words checked' => $wordCount,
                'Long sentence rate' => $longSentenceRate . '%',
                'Sentence length variance' => $sentenceVariance,
                'Lexical diversity' => $lexicalDiversity . '%',
                'Repeated phrase groups' => $repeatedPhraseCount,
            ];
            $provider = 'Local paraphrase indicators';
        } else {
            $sources = $this->findMatchingInternetSnippets($text);
            $systemMatches = $this->findSystemSubmissionMatches($text, $submission);
            $sources = array_merge($systemMatches['matches'], $sources);
            $score = min(100, max(0, (int) round(
                min($repeatedPhraseCount * 10, 35)
                + ($lexicalDiversity < 35 ? 15 : 5)
                + (count($systemMatches['matches']) > 0 ? 50 + (count($systemMatches['matches']) * 10) : 0)
                + (count($sources) > count($systemMatches['matches']) ? 25 : 0)
            )));
            $metrics = [
                'Words checked' => $wordCount,
                'System submissions checked' => $systemMatches['checked'],
                'System matches found' => count($systemMatches['matches']),
                'Unreadable system submissions' => $systemMatches['unreadable'],
                'Total matches found' => count($sources),
                'Repeated phrase groups' => $repeatedPhraseCount,
                'Lexical diversity' => $lexicalDiversity . '%',
                'Longest repeated phrase size' => '4 words',
            ];
            $provider = count($sources) > 0 ? 'System submissions + Google Custom Search + local plagiarism indicators' : 'System submissions + local plagiarism indicators';
        }

        return [
            'title' => $labels[$checkType],
            'score' => $score,
            'status' => $this->riskLabel($score),
            'summary' => $this->localIntegritySummary($checkType, $score, count($sources)),
            'checked_at' => now()->format('Y-m-d H:i:s'),
            'metrics' => $metrics,
            'sources' => $sources,
            'provider' => $provider,
            'note' => $this->integrityProviderNote($checkType),
        ];
    }

    protected function integritySourcePdfHtml($data, AssignmentSubmission $submission)
    {
        $matchedTexts = $data['matched_texts'] ?? [];
        $rows = '';

        foreach ($matchedTexts as $match) {
            $rows .= '<div class="match-block">
                <p class="label">Matched phrase</p>
                <p class="phrase">' . e($match['matched_phrase'] ?? '-') . '</p>
                <table>
                    <tr>
                        <th>Current Checked Text</th>
                        <th>Matched Stored Submission Text</th>
                    </tr>
                    <tr>
                        <td>' . nl2br(e($match['current_text'] ?? '-')) . '</td>
                        <td>' . nl2br(e($match['matched_submission_text'] ?? '-')) . '</td>
                    </tr>
                </table>
            </div>';
        }

        if ($rows === '') {
            $rows = '<p>No copied-text excerpts were available for this source.</p>';
        }

        return '<!doctype html>
            <html>
            <head>
                <meta charset="utf-8">
                <style>
                    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
                    h1 { font-size: 20px; margin-bottom: 4px; }
                    h2 { font-size: 15px; margin-top: 18px; }
                    .meta { color: #666; margin-bottom: 14px; }
                    .summary { border: 1px solid #ddd; padding: 10px; margin-bottom: 14px; }
                    .match-block { page-break-inside: avoid; margin-top: 12px; }
                    .label { color: #666; font-size: 11px; margin-bottom: 2px; }
                    .phrase { background: #fff3cd; border: 1px solid #f1d88b; padding: 6px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 8px; }
                    th, td { border: 1px solid #ddd; padding: 7px; vertical-align: top; width: 50%; }
                    th { background: #f5f5f5; }
                </style>
            </head>
            <body>
                <h1>Plagiarism Match Export</h1>
                <div class="meta">
                    Assignment: ' . e(optional($submission->assignment)->name) . '<br>
                    Student: ' . e(userName('Student', $submission->student_id)) . '<br>
                    Exported at: ' . e(now()->format('Y-m-d H:i:s')) . '
                </div>
                <div class="summary">
                    <strong>Source:</strong> ' . e($data['title'] ?? '-') . '<br>
                    <strong>Similarity Score:</strong> ' . e((string) ($data['score'] ?? '-')) . '<br>
                    <strong>Link:</strong> ' . e($data['link'] ?? '-') . '<br>
                    <strong>Summary:</strong> ' . e($data['snippet'] ?? '-') . '
                </div>
                <h2>Copied Text Evidence</h2>
                ' . $rows . '
            </body>
            </html>';
    }

    private function callConfiguredIntegrityProvider($checkType, $text, AssignmentSubmission $submission)
    {
        $config = [
            'ai_detection' => [
                'url' => env('AI_DETECTION_API_URL'),
                'token' => env('AI_DETECTION_API_TOKEN'),
                'title' => 'AI Detection',
            ],
            'paraphraser' => [
                'url' => env('PARAPHRASER_CHECK_API_URL'),
                'token' => env('PARAPHRASER_CHECK_API_TOKEN'),
                'title' => 'Paraphraser Checking',
            ],
            'plagiarism' => [
                'url' => env('PLAGIARISM_CHECK_API_URL'),
                'token' => env('PLAGIARISM_CHECK_API_TOKEN'),
                'title' => 'Plagiarism Checking',
            ],
        ][$checkType];

        if (empty($config['url'])) {
            return null;
        }

        try {
            $request = Http::acceptJson()->timeout(60);
            if (!empty($config['token'])) {
                $request = $request->withToken($config['token']);
            }

            $response = $request->post($config['url'], [
                'text' => $text,
                'submission_id' => $submission->id,
                'assignment_id' => $submission->assignment_id,
                'student_id' => $submission->student_id,
                'check_type' => $checkType,
            ]);

            if (!$response->successful()) {
                Log::warning('Integrity provider request failed', [
                    'check_type' => $checkType,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();
            $score = (int) ($data['score'] ?? $data['percentage'] ?? $data['risk_score'] ?? 0);

            return [
                'title' => $config['title'],
                'score' => min(100, max(0, $score)),
                'status' => $data['status'] ?? $this->riskLabel($score),
                'summary' => $data['summary'] ?? $data['message'] ?? 'External checker completed successfully.',
                'checked_at' => now()->format('Y-m-d H:i:s'),
                'metrics' => $data['metrics'] ?? [],
                'sources' => $data['sources'] ?? $data['matches'] ?? [],
                'provider' => parse_url($config['url'], PHP_URL_HOST) ?: 'Configured provider',
                'note' => null,
            ];
        } catch (\Throwable $exception) {
            Log::warning('Integrity provider exception', [
                'check_type' => $checkType,
                'message' => $exception->getMessage(),
            ]);
            return null;
        }
    }

    private function findMatchingInternetSnippets($text)
    {
        if (!env('GOOGLE_CSE_API_KEY') || !env('GOOGLE_CSE_CX')) {
            return [];
        }

        $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $sources = [];

        foreach (array_slice($sentences, 0, 4) as $sentence) {
            $query = trim(Str::limit($sentence, 140, ''));
            if (str_word_count($query) < 8) {
                continue;
            }

            try {
                $response = Http::timeout(20)->get('https://www.googleapis.com/customsearch/v1', [
                    'key' => env('GOOGLE_CSE_API_KEY'),
                    'cx' => env('GOOGLE_CSE_CX'),
                    'q' => '"' . $query . '"',
                    'num' => 3,
                ]);

                if (!$response->successful()) {
                    continue;
                }

                foreach (($response->json('items') ?? []) as $item) {
                    $sources[] = [
                        'title' => $item['title'] ?? 'Matched source',
                        'link' => $item['link'] ?? null,
                        'snippet' => $item['snippet'] ?? null,
                    ];
                }
            } catch (\Throwable $exception) {
                Log::warning('Internet snippet search failed', ['message' => $exception->getMessage()]);
            }
        }

        return array_values(array_slice($sources, 0, 6));
    }

    private function findSystemSubmissionMatches($text, AssignmentSubmission $submission)
    {
        $matches = [];
        $checked = 0;
        $unreadable = 0;
        $assignmentIds = $this->sameContextAssignmentIds($submission);
        $otherSubmissions = AssignmentSubmission::with(['student', 'assignment'])
            ->whereIn('assignment_id', $assignmentIds)
            ->where('id', '!=', $submission->id)
            ->where('student_id', '!=', $submission->student_id)
            ->whereNotNull('path')
            ->get();

        foreach ($otherSubmissions as $otherSubmission) {
            $storedText = $this->extractStoredSubmissionText($otherSubmission->path);

            if (trim($storedText) === '') {
                $unreadable++;
                continue;
            }

            $checked++;
            $similarity = $this->textSimilarityScore($text, $storedText);

            if ($similarity < 18) {
                continue;
            }

            $matches[] = [
                'title' => userName('Student', $otherSubmission->student_id) . ' - Internal submission match',
                'link' => asset($otherSubmission->path),
                'snippet' => 'Similarity with stored submission: ' . $similarity . '%. Assignment: ' . optional($otherSubmission->assignment)->name . '. Assignment submission ID: ' . $otherSubmission->id,
                'match_type' => 'system_submission',
                'score' => $similarity,
                'matched_texts' => $this->matchingTextExcerpts($text, $storedText),
            ];
        }

        $otherAnswers = AssignmentAnswer::with(['assignmentQuestion', 'assignmentSubmission.assignment'])
            ->whereHas('assignmentSubmission', function ($query) use ($assignmentIds, $submission) {
                $query->whereIn('assignment_id', $assignmentIds)
                    ->where('id', '!=', $submission->id)
                    ->where('student_id', '!=', $submission->student_id);
            })
            ->whereNotNull('answer')
            ->get();

        foreach ($otherAnswers as $otherAnswer) {
            $storedText = trim((string) $otherAnswer->answer);

            if ($storedText === '') {
                $unreadable++;
                continue;
            }

            $checked++;
            $similarity = $this->textSimilarityScore($text, $storedText);

            if ($similarity < 18) {
                continue;
            }

            $otherSubmission = $otherAnswer->assignmentSubmission;
            $matches[] = [
                'title' => userName('Student', optional($otherSubmission)->student_id) . ' - Internal answer match',
                'link' => null,
                'snippet' => 'Similarity with stored answer: ' . $similarity . '%. Assignment: ' . optional(optional($otherSubmission)->assignment)->name . '. Question: ' . optional($otherAnswer->assignmentQuestion)->question . '. Assignment submission ID: ' . optional($otherSubmission)->id,
                'match_type' => 'system_answer',
                'score' => $similarity,
                'matched_texts' => $this->matchingTextExcerpts($text, $storedText),
            ];
        }

        usort($matches, function ($first, $second) {
            return ($second['score'] ?? 0) <=> ($first['score'] ?? 0);
        });

        return [
            'matches' => array_slice($matches, 0, 8),
            'checked' => $checked,
            'unreadable' => $unreadable,
        ];
    }

    private function extractStoredSubmissionText($path)
    {
        $filePath = public_path($path);

        if (!is_file($filePath)) {
            return '';
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($extension === 'pdf') {
            return $this->extractPdfTextFromFile($filePath);
        }

        if (in_array($extension, ['docx', 'doc'], true)) {
            return $this->extractWordTextFromFile($filePath);
        }

        if (in_array($extension, ['txt', 'csv'], true)) {
            return file_get_contents($filePath) ?: '';
        }

        return '';
    }

    private function extractPdfTextFromFile($filePath)
    {
        $contents = file_get_contents($filePath);
        if ($contents === false) {
            return '';
        }

        $objects = $this->extractPdfObjects($contents);
        $fontMaps = $this->extractPdfFontUnicodeMaps($objects);
        $texts = [];

        foreach ($objects as $object) {
            if ($object['stream'] === null) {
                continue;
            }

            $decodedStream = $this->decodePdfStream($object['stream'], $object['body']);
            if ($decodedStream === '') {
                continue;
            }

            $texts[] = $this->extractPdfTextFromContentStream($decodedStream, $fontMaps);
        }

        return trim(preg_replace('/\s+/', ' ', implode(' ', $texts)));
    }

    private function extractPdfObjects($contents)
    {
        $objects = [];
        preg_match_all('/(\d+)\s+\d+\s+obj(.*?)endobj/s', $contents, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $body = $match[2];
            $stream = null;

            if (preg_match('/stream\r?\n(.*?)\r?\nendstream/s', $body, $streamMatch)) {
                $stream = $streamMatch[1];
            }

            $objects[(int) $match[1]] = [
                'body' => $body,
                'stream' => $stream,
            ];
        }

        return $objects;
    }

    private function decodePdfStream($stream, $objectBody)
    {
        $stream = trim($stream);

        if (strpos($objectBody, '/FlateDecode') !== false) {
            $decoded = @gzuncompress($stream);
            if ($decoded === false) {
                $decoded = @gzdecode($stream);
            }
            if ($decoded === false) {
                $decoded = @gzinflate($stream);
            }

            return $decoded === false ? '' : $decoded;
        }

        return $stream;
    }

    private function extractPdfFontUnicodeMaps($objects)
    {
        $toUnicodeMaps = [];
        $fontObjectMaps = [];
        $fontResourceMaps = [];

        foreach ($objects as $objectId => $object) {
            if ($object['stream'] !== null) {
                $decodedStream = $this->decodePdfStream($object['stream'], $object['body']);
                if (strpos($decodedStream, 'beginbf') !== false) {
                    $toUnicodeMaps[$objectId] = $this->parseToUnicodeMap($decodedStream);
                }
            }

            if (preg_match('/\/ToUnicode\s+(\d+)\s+\d+\s+R/', $object['body'], $toUnicodeMatch)) {
                $fontObjectMaps[$objectId] = (int) $toUnicodeMatch[1];
            }

            preg_match_all('/\/(F\d+)\s+(\d+)\s+\d+\s+R/', $object['body'], $fontMatches, PREG_SET_ORDER);
            foreach ($fontMatches as $fontMatch) {
                $fontResourceMaps[$fontMatch[1]] = (int) $fontMatch[2];
            }
        }

        $fontMaps = [];
        foreach ($fontResourceMaps as $fontName => $fontObjectId) {
            $toUnicodeObjectId = $fontObjectMaps[$fontObjectId] ?? null;
            if ($toUnicodeObjectId && isset($toUnicodeMaps[$toUnicodeObjectId])) {
                $fontMaps[$fontName] = $toUnicodeMaps[$toUnicodeObjectId];
            }
        }

        return $fontMaps;
    }

    private function parseToUnicodeMap($mapContent)
    {
        $map = [];

        preg_match_all('/<([0-9A-Fa-f]+)>\s+<([0-9A-Fa-f]+)>/', $mapContent, $charMatches, PREG_SET_ORDER);
        foreach ($charMatches as $charMatch) {
            $map[strtoupper($charMatch[1])] = $this->unicodeHexToUtf8($charMatch[2]);
        }

        preg_match_all('/<([0-9A-Fa-f]+)>\s+<([0-9A-Fa-f]+)>\s+\[(.*?)\]/s', $mapContent, $rangeMatches, PREG_SET_ORDER);
        foreach ($rangeMatches as $rangeMatch) {
            $start = hexdec($rangeMatch[1]);
            preg_match_all('/<([0-9A-Fa-f]+)>/', $rangeMatch[3], $values);
            foreach ($values[1] ?? [] as $offset => $value) {
                $map[strtoupper(str_pad(dechex($start + $offset), strlen($rangeMatch[1]), '0', STR_PAD_LEFT))] = $this->unicodeHexToUtf8($value);
            }
        }

        preg_match_all('/<([0-9A-Fa-f]+)>\s+<([0-9A-Fa-f]+)>\s+<([0-9A-Fa-f]+)>/', $mapContent, $sequentialRangeMatches, PREG_SET_ORDER);
        foreach ($sequentialRangeMatches as $rangeMatch) {
            $start = hexdec($rangeMatch[1]);
            $end = hexdec($rangeMatch[2]);
            $unicodeStart = hexdec($rangeMatch[3]);

            for ($code = $start; $code <= $end; $code++) {
                $map[strtoupper(str_pad(dechex($code), strlen($rangeMatch[1]), '0', STR_PAD_LEFT))] = $this->unicodeCodepointToUtf8($unicodeStart + ($code - $start));
            }
        }

        return $map;
    }

    private function extractPdfTextFromContentStream($stream, $fontMaps)
    {
        $texts = [];
        $currentFont = null;
        $fallbackMap = [];
        foreach ($fontMaps as $fontMap) {
            $fallbackMap = $fallbackMap + $fontMap;
        }

        preg_match_all('/\/F\d+\s+\d+(?:\.\d+)?\s+Tf|\[(?:[^\[\]]|\([^)]*\)|<[^>]*>)*\]\s*TJ|<([0-9A-Fa-f]+)>\s*Tj|\((?:\\\\.|[^\\\\)])*\)\s*Tj/s', $stream, $tokens);

        foreach ($tokens[0] ?? [] as $token) {
            if (preg_match('/\/(F\d+)\s+\d+(?:\.\d+)?\s+Tf/', $token, $fontMatch)) {
                $currentFont = $fontMatch[1];
                continue;
            }

            if (preg_match('/^<([0-9A-Fa-f]+)>\s*Tj$/s', trim($token), $hexMatch)) {
                $texts[] = $this->decodePdfHexText($hexMatch[1], $fontMaps[$currentFont] ?? $fallbackMap);
                continue;
            }

            if (preg_match('/^\[(.*?)\]\s*TJ$/s', trim($token), $arrayMatch)) {
                preg_match_all('/<([0-9A-Fa-f]+)>|\((?:\\\\.|[^\\\\)])*\)/s', $arrayMatch[1], $parts);
                foreach ($parts[0] ?? [] as $part) {
                    if (preg_match('/^<([0-9A-Fa-f]+)>$/', $part, $hexPart)) {
                        $texts[] = $this->decodePdfHexText($hexPart[1], $fontMaps[$currentFont] ?? $fallbackMap);
                    } else {
                        $texts[] = $this->decodePdfTextToken($part);
                    }
                }
                continue;
            }

            if (preg_match('/^(\((?:\\\\.|[^\\\\)])*\))\s*Tj$/s', trim($token), $textMatch)) {
                $texts[] = $this->decodePdfTextToken($textMatch[1]);
            }
        }

        return implode('', $texts);
    }

    private function decodePdfHexText($hex, $unicodeMap)
    {
        $hex = strtoupper($hex);
        $text = '';

        for ($i = 0; $i < strlen($hex); $i += 2) {
            $code = substr($hex, $i, 2);
            if (isset($unicodeMap[$code]) || isset($unicodeMap[ltrim($code, '0')])) {
                $text .= $unicodeMap[$code] ?? $unicodeMap[ltrim($code, '0')];
                continue;
            }

            $decimal = hexdec($code);
            $text .= ($decimal >= 32 && $decimal <= 126) ? chr($decimal) : ' ';
        }

        return $text;
    }

    private function unicodeHexToUtf8($hex)
    {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding(pack('H*', $hex), 'UTF-8', 'UTF-16BE');
        }

        return $this->unicodeCodepointToUtf8(hexdec(substr($hex, -4)));
    }

    private function unicodeCodepointToUtf8($codepoint)
    {
        if ($codepoint < 32) {
            return ' ';
        }

        return html_entity_decode('&#' . $codepoint . ';', ENT_NOQUOTES, 'UTF-8');
    }

    private function decodePdfTextToken($token)
    {
        $token = substr($token, 1, -1);
        $token = str_replace(['\\(', '\\)', '\\\\'], ['(', ')', '\\'], $token);
        $token = preg_replace_callback('/\\\\([0-7]{1,3})/', function ($match) {
            return chr(octdec($match[1]));
        }, $token);

        return $token;
    }

    private function extractWordTextFromFile($filePath)
    {
        if (!class_exists(\ZipArchive::class)) {
            return '';
        }

        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            return '';
        }

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if ($xml === false) {
            return '';
        }

        return trim(preg_replace('/\s+/', ' ', strip_tags($xml)));
    }

    private function textSimilarityScore($text, $storedText)
    {
        $currentShingles = $this->wordShingles($text);
        $storedShingles = $this->wordShingles($storedText);

        if (count($currentShingles) === 0 || count($storedShingles) === 0) {
            return 0;
        }

        $intersection = count(array_intersect_key($currentShingles, $storedShingles));
        $shorterSet = min(count($currentShingles), count($storedShingles));

        return round(($intersection / max($shorterSet, 1)) * 100, 1);
    }

    private function matchingTextExcerpts($text, $storedText)
    {
        $currentWords = str_word_count($text, 1);
        $storedWords = str_word_count($storedText, 1);
        $storedShingles = [];
        $matches = [];

        for ($i = 0; $i < count($storedWords) - 5; $i++) {
            $key = strtolower(implode(' ', array_slice($storedWords, $i, 6)));
            $storedShingles[$key] = $i;
        }

        for ($i = 0; $i < count($currentWords) - 5; $i++) {
            $key = strtolower(implode(' ', array_slice($currentWords, $i, 6)));

            if (!isset($storedShingles[$key]) || isset($matches[$key])) {
                continue;
            }

            $storedIndex = $storedShingles[$key];
            $matches[$key] = [
                'matched_phrase' => implode(' ', array_slice($currentWords, $i, 6)),
                'current_text' => $this->wordExcerpt($currentWords, $i),
                'matched_submission_text' => $this->wordExcerpt($storedWords, $storedIndex),
            ];

            if (count($matches) >= 6) {
                break;
            }
        }

        return array_values($matches);
    }

    private function wordExcerpt($words, $index)
    {
        $start = max(0, $index - 8);
        $excerptWords = array_slice($words, $start, 24);
        $prefix = $start > 0 ? '... ' : '';
        $suffix = ($start + 24) < count($words) ? ' ...' : '';

        return $prefix . implode(' ', $excerptWords) . $suffix;
    }

    private function wordShingles($text)
    {
        $words = str_word_count(strtolower($text), 1);
        $shingles = [];

        for ($i = 0; $i < count($words) - 5; $i++) {
            $shingles[implode(' ', array_slice($words, $i, 6))] = true;
        }

        return $shingles;
    }

    private function countRepeatedPhrases($text)
    {
        $words = str_word_count(strtolower($text), 1);
        $phrases = [];

        for ($i = 0; $i < count($words) - 3; $i++) {
            $phrase = implode(' ', array_slice($words, $i, 4));
            $phrases[$phrase] = ($phrases[$phrase] ?? 0) + 1;
        }

        return count(array_filter($phrases, function ($count) {
            return $count > 1;
        }));
    }

    private function sentenceLengthVariance($sentences)
    {
        $lengths = [];

        foreach ($sentences as $sentence) {
            $lengths[] = str_word_count($sentence);
        }

        if (count($lengths) === 0) {
            return 0;
        }

        $average = array_sum($lengths) / count($lengths);
        $variance = 0;

        foreach ($lengths as $length) {
            $variance += pow($length - $average, 2);
        }

        return round(sqrt($variance / count($lengths)), 1);
    }

    private function longSentenceRate($sentences)
    {
        if (count($sentences) === 0) {
            return 0;
        }

        $longSentences = 0;

        foreach ($sentences as $sentence) {
            if (str_word_count($sentence) >= 28) {
                $longSentences++;
            }
        }

        return round(($longSentences / count($sentences)) * 100, 1);
    }

    private function transitionPhraseRate($text, $wordCount)
    {
        if ($wordCount === 0) {
            return 0;
        }

        $phrases = [
            'moreover',
            'furthermore',
            'in conclusion',
            'it is important to note',
            'as a result',
            'therefore',
            'however',
            'overall',
        ];
        $matches = 0;
        $lowerText = strtolower($text);

        foreach ($phrases as $phrase) {
            $matches += substr_count($lowerText, $phrase);
        }

        return round(($matches / $wordCount) * 100, 2);
    }

    private function riskLabel($score)
    {
        if ($score >= 70) {
            return 'High';
        }

        if ($score >= 40) {
            return 'Medium';
        }

        return 'Low';
    }

    private function localIntegritySummary($checkType, $score, $sourceCount)
    {
        if ($checkType === 'plagiarism' && $sourceCount > 0) {
            return 'Possible internet matches were found. Review the matched sources before grading.';
        }

        if ($checkType === 'ai_detection') {
            return 'The result is based on writing-pattern indicators because no AI detection provider is configured.';
        }

        if ($checkType === 'paraphraser') {
            return 'The result is based on sentence uniformity, vocabulary diversity, and repeated phrase indicators.';
        }

        return $score >= 40
            ? 'Some originality risk indicators were found in the extracted PDF text.'
            : 'No strong originality risk indicators were found in the extracted PDF text.';
    }

    private function integrityProviderNote($checkType)
    {
        if ($checkType === 'plagiarism' && !env('GOOGLE_CSE_API_KEY')) {
            return 'Configure PLAGIARISM_CHECK_API_URL or GOOGLE_CSE_API_KEY and GOOGLE_CSE_CX to run live internet matching.';
        }

        return 'Configure the related API URL in .env to use a dedicated external checker for this result.';
    }

    private function sanitizeForJson($value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $this->sanitizeForJson($item);
            }

            return $value;
        }

        if (!is_string($value)) {
            return $value;
        }

        $value = str_replace("\0", '', $value);

        if (function_exists('mb_check_encoding') && mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($value, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
        }

        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
            if ($converted !== false) {
                return $converted;
            }
        }

        return preg_replace('/[^\x09\x0A\x0D\x20-\x7E]/', ' ', $value);
    }
}
