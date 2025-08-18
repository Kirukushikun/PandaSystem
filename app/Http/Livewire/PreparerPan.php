<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;
use Illuminate\Support\Facades\Log;

class PreparerPan extends Component
{   
    public $requestID, $requestEntry;
    public $date_hired, $employment_status, $division, $date_of_effectivity;
    public 
        $section_from, $section_to,
        $place_from, $place_to,
        $head_from, $head_to,
        $position_from, $position_to,
        $joblevel_from, $joblevel_to,
        $basic_from, $basic_to;

    public $allowances = []; 

    public function mount($requestID = null){
        if($requestID){
            $this->requestID = $requestID;
            $this->requestEntry = RequestorModel::findOrFail($requestID);            
        }
    }

    // Add this method to receive allowances from Alpine.js
    public function updateAllowances($allowances)
    {
        $this->allowances = $allowances;
        Log::info('Allowances updated from Alpine.js:', $this->allowances);
    }

    public function submitPan(){
        // Log static fields
        Log::info("Static fields", [
            'date_hired' => $this->date_hired,
            'employment_status' => $this->employment_status,
            'division' => $this->division,
            'date_of_effectivity' => $this->date_of_effectivity,
            'section' => [$this->section_from, $this->section_to],
            'place' => [$this->place_from, $this->place_to],
            'head' => [$this->head_from, $this->head_to],
            'position' => [$this->position_from, $this->position_to],
            'joblevel' => [$this->joblevel_from, $this->joblevel_to],
            'basic' => [$this->basic_from, $this->basic_to],
        ]);

        // Log allowances
        Log::info('Final allowances for submission:', $this->allowances);

        // Process allowances
        if (!empty($this->allowances)) {
            foreach ($this->allowances as $index => $allowance) {
                Log::info("Allowance {$index}:", [
                    'type' => $allowance['value'] ?? 'N/A',
                    'from' => $allowance['from'] ?? 'N/A',
                    'to' => $allowance['to'] ?? 'N/A'
                ]);
            }
        } else {
            Log::info('No allowances to process');
        }

        // Your submission logic here...
        
        session()->flash('message', 'PAN form submitted successfully!');
    }

    public function render()
    {
        return view('livewire.preparer-pan');
    }
}