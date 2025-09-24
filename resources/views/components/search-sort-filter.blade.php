<!-- Search -->
<div class="search-bar flex items-center flex-initial w-sm px-5 py-2 ml-auto border-solid border-2 border-gray-300 bg-gray-100 rounded-lg">
    <input type="text" name="search-input" id="search-input" class="search-input bg-gray-100 p-0 w-full border-none outline-none focus:ring-0" placeholder="Search..." wire:model.live="search">
    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
</div>


@if($farmFilter)
    @if(Auth()->user()->access['HRP_Module'] == true || Auth()->user()->access['HRA_Module'] == true)
        <select name="filterFarm" id="filterFarm"
            class="filterFarm flex-none border-solid border-2 border-gray-300 bg-gray-200 rounded-md"
            wire:model.live="filterFarm">
            <option value="">Filter by Farm</option>
            <option value="BFC">BFC</option>
            <option value="BBG">BBG</option>
            <option value="BRD">BRD</option>
            <option value="HCF">HCF</option>
            <option value="PFC">PFC</option>
        </select>
    @endif
@endif

@php
    $optionsByRole = [
        'requestor' => [
            'Draft', 'For Head Approval', 'For HR Prep', 'For Confirmation',
            'For HR Approval', 'For Resolution', 'For Final Approval',
            'Returned to Requestor', 'Withdrew', 'Approved', 'Served', 'Filed',
        ],
        'divisionhead' => [
            'For Head Approval', 'For HR Prep', 'For Confirmation', 'For HR Approval',
            'For Resolution', 'For Final Approval', 'Returned to Requestor', 'Withdrew', 'Approved', 'Served', 'Filed',
        ],
        'hrpreparer' => ['For HR Prep', 'For Confirmation', 'For Resolution', 'Approved', 'Served', 'Filed'],
        'hrapprover' => ['For HR Approval', 'For Final Approval', 'Approved', 'Served', 'Filed'],
        'finalapprover' => ['For Final Approval', 'Approved', 'Served', 'Filed'],
    ];

    $filterOptions = $optionsByRole[$role];
@endphp

<!-- Filter -->
<select name="filterby" id="filterby"
        class="filterby flex-none border-solid border-2 border-gray-300 bg-gray-200 rounded-md"
        wire:model.live="filterBy">
    <option value="">Filter by Status</option>
    @foreach($filterOptions as $option)
        <option value="{{ $option }}">{{ $option == 'Returned to Requestor' ? 'Returned' : $option }}</option>
    @endforeach
</select>