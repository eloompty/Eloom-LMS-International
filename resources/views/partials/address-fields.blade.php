@php
    $address = $address ?? null;
    $addressPrefix = $addressPrefix ?? '';
    // Bracketed array mode for the repeatable multi-address list: names become
    // "{arrayName}[{index}][{field}]" instead of the flat "{prefix}{field}".
    $addressArrayName = $addressArrayName ?? null;
    $addressIndex = $addressIndex ?? null;
    // When the country select is rendered by the surrounding block, suppress the one here.
    $hideCountrySelect = $hideCountrySelect ?? false;
    $addressIdPrefix = $addressIdPrefix ?? (trim($addressPrefix, '_') ?: 'address');
    $format = $addressFormat ?? currentAddressFormat();
    $countryOptions = \Modules\Country\Entities\Country::where('status', 1)->pluck('name', 'id');

    $fieldName = function ($field) use ($addressPrefix, $addressArrayName, $addressIndex) {
        if ($addressArrayName !== null) {
            return $addressArrayName . '[' . $addressIndex . '][' . $field . ']';
        }
        return $addressPrefix . $field;
    };
    $fieldId = function ($field) use ($addressIdPrefix) {
        return $addressIdPrefix . '_' . $field;
    };
    $value = function ($field) use ($address, $addressPrefix, $addressArrayName, $addressIndex) {
        if ($addressArrayName !== null) {
            return old($addressArrayName . '.' . $addressIndex . '.' . $field, $address ? ($address->{$field} ?? null) : null);
        }
        return old($addressPrefix . $field, $address ? $address->{$field} : null);
    };
@endphp

@if($format === 'nepal')
@php
    $districts = $address && $address->province ? \Modules\Location\Entities\Location::where('status', 1)->where('location_id', $address->province)->pluck('name', 'id') : collect();
    $localBodies = $address && $address->district ? \Modules\Location\Entities\Location::where('status', 1)->where('location_id', $address->district)->pluck('name', 'id') : collect();
    $wards = $address && $address->local_body ? \Modules\Location\Entities\Location::where('status', 1)->where('location_id', $address->local_body)->pluck('name', 'id') : collect();
    $toles = $address && $address->ward ? \Modules\Location\Entities\Location::where('status', 1)->where('location_id', $address->ward)->pluck('name', 'id') : collect();
@endphp
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('province') }}">Province <span class="required">*</span></label>
    <select id="{{ $fieldId('province') }}" name="{{ $fieldName('province') }}" class="form-control" required>
        <option value="">-- Select Province --</option>
        @foreach(getLocations() as $key => $label)
        <option value="{{ $key }}" @if($value('province') == $key) selected @endif>{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('district') }}">District <span class="required">*</span></label>
    <select name="{{ $fieldName('district') }}" id="{{ $fieldId('district') }}" class="form-control" required>
        <option value="">-- Select District --</option>
        @foreach($districts as $key => $label)
        <option value="{{ $key }}" @if($value('district') == $key) selected @endif>{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('local_body') }}">Local Body <span class="required">*</span></label>
    <select name="{{ $fieldName('local_body') }}" id="{{ $fieldId('local_body') }}" class="form-control" required>
        <option value="">-- Select Local Body --</option>
        @foreach($localBodies as $key => $label)
        <option value="{{ $key }}" @if($value('local_body') == $key) selected @endif>{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('ward') }}">Ward <span class="required">*</span></label>
    <select name="{{ $fieldName('ward') }}" id="{{ $fieldId('ward') }}" class="form-control" required>
        <option value="">-- Select Ward --</option>
        @foreach($wards as $key => $label)
        <option value="{{ $key }}" @if($value('ward') == $key) selected @endif>{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('tole') }}">Tole <span class="required">*</span></label>
    <select name="{{ $fieldName('tole') }}" id="{{ $fieldId('tole') }}" class="form-control" required>
        <option value="">-- Select Tole --</option>
        @foreach($toles as $key => $label)
        <option value="{{ $key }}" @if($value('tole') == $key) selected @endif>{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('address') }}">Address <span class="required">*</span></label>
    <input type="text" class="form-control" id="{{ $fieldId('address') }}" placeholder="Address" name="{{ $fieldName('address') }}" value="{{ $value('address') }}" required>
</div>
@elseif($format === 'australia')
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('building_number') }}">Building Name</label>
    <input type="text" name="{{ $fieldName('building_number') }}" class="form-control" id="{{ $fieldId('building_number') }}" value="{{ $value('building_number') ?: $value('building_name') }}" placeholder="Enter Building Name">
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('flat_unit') }}">Flat/Unit</label>
    <input type="text" name="{{ $fieldName('flat_unit') }}" class="form-control" id="{{ $fieldId('flat_unit') }}" value="{{ $value('flat_unit') }}" placeholder="Enter Flat/Unit">
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('street_no') }}">Street No <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('street_no') }}" class="form-control" id="{{ $fieldId('street_no') }}" value="{{ $value('street_no') }}" placeholder="Enter Street No" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('street_address') }}">Street Address <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('street_address') }}" class="form-control" id="{{ $fieldId('street_address') }}" value="{{ $value('street_address') }}" placeholder="Enter Street Address" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('p_o_box') }}">P.O Box</label>
    <input type="text" name="{{ $fieldName('p_o_box') }}" class="form-control" id="{{ $fieldId('p_o_box') }}" value="{{ $value('p_o_box') }}" placeholder="Enter P.O Box">
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('suburb') }}">Suburb <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('suburb') }}" class="form-control" id="{{ $fieldId('suburb') }}" value="{{ $value('suburb') }}" placeholder="Enter Suburb" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('state') }}">State <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('state') }}" class="form-control" id="{{ $fieldId('state') }}" value="{{ $value('state') }}" placeholder="Enter State" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('postal_code') }}">Postal Code <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('postal_code') }}" class="form-control" id="{{ $fieldId('postal_code') }}" value="{{ $value('postal_code') ?: $value('zip_code') }}" placeholder="Enter Postal Code" required>
</div>
@elseif($format === 'india')
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('building_name') }}">House/Building <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('building_name') }}" class="form-control" id="{{ $fieldId('building_name') }}" value="{{ $value('building_name') ?: $value('building_number') }}" placeholder="Enter House/Building" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('street_address') }}">Street Address <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('street_address') }}" class="form-control" id="{{ $fieldId('street_address') }}" value="{{ $value('street_address') }}" placeholder="Enter Street Address" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('area') }}">Area/Locality <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('area') }}" class="form-control" id="{{ $fieldId('area') }}" value="{{ $value('area') }}" placeholder="Enter Area/Locality" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('city') }}">City <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('city') }}" class="form-control" id="{{ $fieldId('city') }}" value="{{ $value('city') }}" placeholder="Enter City" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('district') }}">District <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('district') }}" class="form-control" id="{{ $fieldId('district') }}" value="{{ $value('district') }}" placeholder="Enter District" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('state') }}">State <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('state') }}" class="form-control" id="{{ $fieldId('state') }}" value="{{ $value('state') }}" placeholder="Enter State" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('postal_code') }}">PIN Code <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('postal_code') }}" class="form-control" id="{{ $fieldId('postal_code') }}" value="{{ $value('postal_code') ?: $value('zip_code') }}" placeholder="Enter PIN Code" required>
</div>
@elseif($format === 'usa')
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('address_line_1') }}">Address Line 1 <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('address_line_1') }}" class="form-control" id="{{ $fieldId('address_line_1') }}" value="{{ $value('address_line_1') ?: $value('address') }}" placeholder="Enter Address Line 1" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('address_line_2') }}">Address Line 2</label>
    <input type="text" name="{{ $fieldName('address_line_2') }}" class="form-control" id="{{ $fieldId('address_line_2') }}" value="{{ $value('address_line_2') }}" placeholder="Enter Address Line 2">
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('city') }}">City <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('city') }}" class="form-control" id="{{ $fieldId('city') }}" value="{{ $value('city') }}" placeholder="Enter City" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('state') }}">State <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('state') }}" class="form-control" id="{{ $fieldId('state') }}" value="{{ $value('state') }}" placeholder="Enter State" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('zip_code') }}">ZIP Code <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('zip_code') }}" class="form-control" id="{{ $fieldId('zip_code') }}" value="{{ $value('zip_code') ?: $value('postal_code') }}" placeholder="Enter ZIP Code" required>
</div>
@elseif($format === 'uk')
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('building_name') }}">Building Name/Number <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('building_name') }}" class="form-control" id="{{ $fieldId('building_name') }}" value="{{ $value('building_name') ?: $value('building_number') }}" placeholder="Enter Building Name/Number" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('street_address') }}">Street Address <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('street_address') }}" class="form-control" id="{{ $fieldId('street_address') }}" value="{{ $value('street_address') }}" placeholder="Enter Street Address" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('address_line_2') }}">Address Line 2</label>
    <input type="text" name="{{ $fieldName('address_line_2') }}" class="form-control" id="{{ $fieldId('address_line_2') }}" value="{{ $value('address_line_2') }}" placeholder="Enter Address Line 2">
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('city') }}">Town/City <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('city') }}" class="form-control" id="{{ $fieldId('city') }}" value="{{ $value('city') }}" placeholder="Enter Town/City" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('county') }}">County</label>
    <input type="text" name="{{ $fieldName('county') }}" class="form-control" id="{{ $fieldId('county') }}" value="{{ $value('county') }}" placeholder="Enter County">
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('postal_code') }}">Postcode <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('postal_code') }}" class="form-control" id="{{ $fieldId('postal_code') }}" value="{{ $value('postal_code') ?: $value('zip_code') }}" placeholder="Enter Postcode" required>
</div>
@elseif($format === 'uae')
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('building_name') }}">Building Name <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('building_name') }}" class="form-control" id="{{ $fieldId('building_name') }}" value="{{ $value('building_name') ?: $value('building_number') }}" placeholder="Enter Building Name" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('flat_unit') }}">Flat/Unit</label>
    <input type="text" name="{{ $fieldName('flat_unit') }}" class="form-control" id="{{ $fieldId('flat_unit') }}" value="{{ $value('flat_unit') }}" placeholder="Enter Flat/Unit">
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('street_address') }}">Street Address <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('street_address') }}" class="form-control" id="{{ $fieldId('street_address') }}" value="{{ $value('street_address') }}" placeholder="Enter Street Address" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('area') }}">Area <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('area') }}" class="form-control" id="{{ $fieldId('area') }}" value="{{ $value('area') }}" placeholder="Enter Area" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('city') }}">City <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('city') }}" class="form-control" id="{{ $fieldId('city') }}" value="{{ $value('city') }}" placeholder="Enter City" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('emirate') }}">Emirate <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('emirate') }}" class="form-control" id="{{ $fieldId('emirate') }}" value="{{ $value('emirate') }}" placeholder="Enter Emirate" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('p_o_box') }}">P.O Box</label>
    <input type="text" name="{{ $fieldName('p_o_box') }}" class="form-control" id="{{ $fieldId('p_o_box') }}" value="{{ $value('p_o_box') }}" placeholder="Enter P.O Box">
</div>
@else
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('address_line_1') }}">Address Line 1 <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('address_line_1') }}" class="form-control" id="{{ $fieldId('address_line_1') }}" value="{{ $value('address_line_1') ?: $value('address') }}" placeholder="Enter Address Line 1" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('address_line_2') }}">Address Line 2</label>
    <input type="text" name="{{ $fieldName('address_line_2') }}" class="form-control" id="{{ $fieldId('address_line_2') }}" value="{{ $value('address_line_2') }}" placeholder="Enter Address Line 2">
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('city') }}">City <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('city') }}" class="form-control" id="{{ $fieldId('city') }}" value="{{ $value('city') }}" placeholder="Enter City" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('state_region') }}">State/Region <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('state_region') }}" class="form-control" id="{{ $fieldId('state_region') }}" value="{{ $value('state_region') ?: $value('state') }}" placeholder="Enter State/Region" required>
</div>
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('postal_code') }}">Postal Code <span class="required">*</span></label>
    <input type="text" name="{{ $fieldName('postal_code') }}" class="form-control" id="{{ $fieldId('postal_code') }}" value="{{ $value('postal_code') ?: $value('zip_code') }}" placeholder="Enter Postal Code" required>
</div>
@endif

@if(!$hideCountrySelect)
@if($format !== 'nepal')
<div class="form-group col-sm-3">
    <label for="{{ $fieldId('country_id') }}">Country <span class="required">*</span></label>
    <select class="form-control" name="{{ $fieldName('country_id') }}" id="{{ $fieldId('country_id') }}" required>
        <option value="">-- Select Country --</option>
        @foreach($countryOptions as $key => $label)
        <option value="{{ $key }}" @if($value('country_id') == $key) selected @endif>{{ $label }}</option>
        @endforeach
    </select>
</div>
@else
<input type="hidden" name="{{ $fieldName('country_id') }}" value="{{ $value('country_id') ?: 156 }}">
@endif
@endif

@if($format === 'nepal')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (!window.jQuery) return;
        var ids = {
            province: '#{{ $fieldId('province') }}',
            district: '#{{ $fieldId('district') }}',
            localBody: '#{{ $fieldId('local_body') }}',
            ward: '#{{ $fieldId('ward') }}',
            tole: '#{{ $fieldId('tole') }}'
        };

        function bindChild(parent, child, placeholder, clearSelectors) {
            jQuery(parent).change(function() {
                var locationID = jQuery(this).val();
                jQuery(child).empty().append('<option value="">' + placeholder + '</option>');
                clearSelectors.forEach(function(selector) {
                    jQuery(selector).empty();
                });
                if (!locationID) return;

                jQuery.ajax({
                    type: 'GET',
                    url: "{{ url('admin/sub_location') }}?location_id=" + locationID,
                    success: function(res) {
                        jQuery.each(res || {}, function(key, label) {
                            jQuery(child).append('<option value="' + key + '">' + label + '</option>');
                        });
                    }
                });
            });
        }

        bindChild(ids.province, ids.district, '-- Select District --', [ids.localBody, ids.ward, ids.tole]);
        bindChild(ids.district, ids.localBody, '-- Select Local Body --', [ids.ward, ids.tole]);
        bindChild(ids.localBody, ids.ward, '-- Select Ward --', [ids.tole]);
        bindChild(ids.ward, ids.tole, '-- Select Tole --', []);
    });
</script>
@endif
