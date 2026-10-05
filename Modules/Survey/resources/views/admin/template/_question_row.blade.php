<div class="dashboard-panel mb-2 question-row" id="q-row-{{ $idx }}"
     style="border-radius:7px;">
    <div class="card-body" style="padding:12px 14px;">
        <div class="row align-items-center">
            <div class="col-md-5 mb-2 mb-md-0">
                <input type="text"
                       name="questions[{{ $idx }}][question]"
                       class="form-control form-control-sm"
                       placeholder="Question text"
                       required
                       style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
            </div>
            <div class="col-md-3 mb-2 mb-md-0">
                <select name="questions[{{ $idx }}][type]"
                        class="form-control form-control-sm"
                        style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                    <option value="text">Open Text</option>
                    <option value="likert">Likert (1–5)</option>
                    <option value="rating">Rating (1–10)</option>
                    <option value="nps">NPS (0–10)</option>
                    <option value="mcq">Multiple Choice</option>
                </select>
            </div>
            <div class="col-md-2 mb-2 mb-md-0">
                <div class="d-flex align-items-center" style="gap:8px;">
                    <input type="checkbox"
                           name="questions[{{ $idx }}][required]"
                           value="1" checked
                           style="width:15px; height:15px; accent-color:#2563eb; cursor:pointer;">
                    <label class="mb-0" style="font-size:12px; font-weight:700; color:#374151; cursor:pointer;">Required</label>
                </div>
            </div>
            <div class="col-md-2 text-right">
                <button type="button"
                        class="panel-action"
                        style="font-size:11px; padding:4px 10px; min-height:28px; color:#dc2626; border-color:#fecaca;"
                        onclick="this.closest('.question-row').remove()">
                    <i class="fas fa-times"></i> Remove
                </button>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <textarea name="questions[{{ $idx }}][options]"
                          class="form-control form-control-sm"
                          rows="2"
                          placeholder="MCQ only — one option per line"
                          style="border-radius:6px; border-color:#d1d5db; font-size:12px; resize:vertical; color:#6b7280;"></textarea>
            </div>
        </div>
    </div>
</div>
