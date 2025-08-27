<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;
use App\Models\PreparerModel;
use Illuminate\Support\Facades\Log;

class PreparerPan extends Component
{   
    public $role = 'approver'; // Temporary role based
    public $mode = 'create';
    public $module;
    public $isDisabled, $requestID, $requestEntry, $panEntry, $referenceTableData;
    public $date_hired, $employment_status, $division, $date_of_effectivity, $remarks;
    public 
        $section_from, $section_to,
        $place_from, $place_to,
        $head_from, $head_to,
        $position_from, $position_to,
        $joblevel_from, $joblevel_to,
        $basic_from, $basic_to;

    public $allowances = []; 

    public function mount($module = null, $requestID = null){
        $this->module = $module;
        
        if($requestID){
            // Request existing entry
            $this->requestID = $requestID;
            $this->requestEntry = RequestorModel::findOrFail($requestID); 

            // Pan existing entry
            $this->panEntry = PreparerModel::where('request_id', $requestID)->first();
            if($this->panEntry){
                $this->referenceTableData = $this->panEntry->action_reference_data;    

                $this->date_hired = optional($this->panEntry->date_hired)->format('Y-m-d');
                $this->date_of_effectivity = optional($this->panEntry->date_of_effectivity)->format('Y-m-d');

                // auto-fill fields (mass assign)
                $this->fill($this->panEntry->only([
                    'employment_status',
                    'division',
                    'remarks'
                ]));

                $this->mode = 'view';
            }

            // disable fields if not Draft or Returned to HR
            if (!in_array($this->requestEntry->request_status, ['For HR Prep', 'Returned to HR'])) {
                $this->isDisabled = true;
            }

        }
    }

    protected $rules = [
        'date_hired' => 'required|date',
        'employment_status' => 'required|string',
        'division' => 'required|string',
        'date_of_effectivity' => 'required|date',
        'remarks' => 'nullable',
    ];

    // Add this method to receive allowances from Alpine.js
    public function updateAllowances($allowances)
    {
        $this->allowances = $allowances;
    }

    public function submitPan(){
        $this->validate();

        $actionReferenceData = [
            // Static section data (with type identifier)
            [
                'field' => 'section',
                'from' => $this->section_from,
                'to' => $this->section_to
            ],
            [
                'field' => 'place',
                'from' => $this->place_from,
                'to' => $this->place_to
            ],
            [
                'field' => 'head',
                'from' => $this->head_from,
                'to' => $this->head_to
            ],
            [
                'field' => 'position',
                'from' => $this->position_from,
                'to' => $this->position_to
            ],
            [
                'field' => 'joblevel',
                'from' => $this->joblevel_from,
                'to' => $this->joblevel_to
            ],
            [
                'field' => 'basic',
                'from' => $this->basic_from,
                'to' => $this->basic_to
            ]
        ];

        // Add allowances to the same array
        foreach ($this->allowances as $allowance) {
            $actionReferenceData[] = [
                'field' => $allowance['value'],
                'from' => $allowance['from'],
                'to' => $allowance['to'],
            ];
        };

        $this->requestEntry->request_status = 'For Confirmation';
        $this->requestEntry->save();

        PreparerModel::create([
            'request_id' => $this->requestID,
            'date_hired' => $this->date_hired,
            'employment_status' => $this->employment_status,
            'division' => $this->division,
            'date_of_effectivity' => $this->date_of_effectivity,
            'action_reference_data' => $actionReferenceData,
            'remarks' => $this->remarks ?? null,
            'prepared_by' => 'Iverson Guno (Preparer)'
        ]);

        $this->dispatch('requestSaved'); // Notify table
        $this->redirect("/hrpreparer");
    }

    public function approveRequest(){
        $this->requestEntry->request_status = 'Approved';
        $this->requestEntry->save();

        $this->dispatch('requestSaved');
        Log::info('Approve reqest');
        $this->redirect('/approver');
    }

    public function rejectRequest(){
        $this->requestEntry->request_status = 'Rejected';
        $this->requestEntry->save();

        $this->dispatch('requestSaved');
        Log::info('Reject reqest');
        $this->redirect('/approver');
    }

    public function render()
    {
        return view('livewire.preparer-pan');
    }
}