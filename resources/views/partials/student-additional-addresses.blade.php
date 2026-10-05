@php
    $student = $student ?? null;
    $existingAddresses = $student ? $student->addresses->where('is_primary', false)->values() : collect();
    $countryOptions = \Modules\Country\Entities\Country::where('status', 1)->pluck('name', 'id');
@endphp

<hr>
<div class="d-flex justify-content-between align-items-center mb-2">
    <h3 class="mb-0">Additional Addresses</h3>
    <button type="button" class="btn btn-sm btn-primary" id="addAddressBtn">+ Add Address</button>
</div>
<p class="text-muted">Optional extra addresses (e.g. Permanent, Home Country, Mailing). Each address uses its own country's format.</p>

<div id="additionalAddresses">
    @foreach($existingAddresses as $i => $addr)
    <div class="card mb-3 address-block" data-index="{{ $i }}">
        <div class="card-body">
            <div class="row">
                <input type="hidden" name="addresses[{{ $i }}][id]" value="{{ $addr->id }}">
                <div class="form-group col-sm-3">
                    <label>Label</label>
                    <input type="text" name="addresses[{{ $i }}][label]" class="form-control"
                        value="{{ old('addresses.'.$i.'.label', $addr->label) }}" placeholder="e.g. Permanent, Home Country">
                </div>
                <div class="form-group col-sm-3">
                    <label>Country <span class="required">*</span></label>
                    <select class="form-control address-country" name="addresses[{{ $i }}][country_id]" data-index="{{ $i }}" required>
                        <option value="">-- Select Country --</option>
                        @foreach($countryOptions as $key => $label)
                        <option value="{{ $key }}" @if(old('addresses.'.$i.'.country_id', $addr->country_id) == $key) selected @endif>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-12">
                    <div class="row address-fields-container">
                        @include('partials.address-fields', [
                            'address' => $addr,
                            'addressFormat' => $addr->address_format ?: addressFormatForCountry($addr->country_id),
                            'addressArrayName' => 'addresses',
                            'addressIndex' => $i,
                            'addressIdPrefix' => 'address_' . $i,
                            'hideCountrySelect' => true,
                        ])
                    </div>
                </div>
                <div class="col-sm-12">
                    <button type="button" class="btn btn-sm btn-danger remove-address">Remove</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Clonable template for new blocks (index placeholder swapped in JS) --}}
<template id="addressBlockTemplate">
    <div class="card mb-3 address-block" data-index="__INDEX__">
        <div class="card-body">
            <div class="row">
                <input type="hidden" name="addresses[__INDEX__][id]" value="">
                <div class="form-group col-sm-3">
                    <label>Label</label>
                    <input type="text" name="addresses[__INDEX__][label]" class="form-control" placeholder="e.g. Permanent, Home Country">
                </div>
                <div class="form-group col-sm-3">
                    <label>Country <span class="required">*</span></label>
                    <select class="form-control address-country" name="addresses[__INDEX__][country_id]" data-index="__INDEX__" required>
                        <option value="">-- Select Country --</option>
                        @foreach($countryOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-12">
                    <div class="row address-fields-container"></div>
                </div>
                <div class="col-sm-12">
                    <button type="button" class="btn btn-sm btn-danger remove-address">Remove</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    (function () {
        var container = document.getElementById('additionalAddresses');
        var addBtn = document.getElementById('addAddressBtn');
        var template = document.getElementById('addressBlockTemplate');
        if (!container || !addBtn || !template) return;

        var nextIndex = {{ $existingAddresses->count() }};
        var fieldsUrl = "{{ route('admin.student.address.fields') }}";

        addBtn.addEventListener('click', function () {
            var html = template.innerHTML.replace(/__INDEX__/g, nextIndex);
            container.insertAdjacentHTML('beforeend', html);
            nextIndex++;
        });

        container.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-address')) {
                var block = e.target.closest('.address-block');
                if (block) block.remove();
            }
        });

        container.addEventListener('change', function (e) {
            if (!e.target.classList.contains('address-country')) return;
            var select = e.target;
            var block = select.closest('.address-block');
            var index = select.getAttribute('data-index');
            var fieldsBox = block.querySelector('.address-fields-container');
            var countryId = select.value;
            if (!countryId) {
                fieldsBox.innerHTML = '';
                return;
            }
            fetch(fieldsUrl + '?country_id=' + encodeURIComponent(countryId) + '&index=' + encodeURIComponent(index), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(function (res) { return res.text(); })
                .then(function (markup) { fieldsBox.innerHTML = markup; })
                .catch(function () { fieldsBox.innerHTML = ''; });
        });
    })();
</script>
