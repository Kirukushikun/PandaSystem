<div class="search-bar flex items-center flex-initial w-sm px-5 py-2 ml-auto border-solid border-2 border-gray-300 bg-gray-100 rounded-lg">
    <input type="text" name="search-input" id="search-input" class="search-input bg-gray-100 p-0 w-full border-none outline-none focus:ring-0" placeholder="Search..." wire:model.live="search">
    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
</div>
<select name="sortby" id="sortby" class="sortby flex-none border-solid border-2 border-gray-300 bg-gray-200 rounded-md" wire:model.live="sortBy">
    <option value="">Sort by Date</option>
    <option value="submitted_at_asc">Submitted (Desc)</option>
    <option value="submitted_at_desc">Submitted (Asc)</option>
    <option value="updated_at_asc">Last Update (Desc)</option>
    <option value="updated_at_desc">Last Update (Asc)</option>
</select>
<select name="filterby" id="filterby" class="filterby flex-none border-solid border-2 border-gray-300 bg-gray-200 rounded-md" wire:model.live="filterBy">
    <option value="">Filter by Status</option>
    <option value="">Draft</option>
    <option value="">For Head Approval</option>
    <option value="">For HR Prep</option>
    <option value="">For Confirmation</option>
    <option value="">For HR Approval</option>
    <option value="">For Resolution</option>
    <option value="">For Final Approval</option>
    <option value="">Returned</option>
    <option value="">Withdrew</option>
    <option value="">Approved</option>
</select>