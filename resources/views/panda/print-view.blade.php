@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />  
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        *{
            font-family: 'Courier'; 
            font-weight: 700;
        }
        body{
            height: auto;
        }
        .outer-border{
            margin: 20px;
            background-color: white;
            outline: 35px solid white;
            border: 3px solid #385623;
        }
        .inner-border{
            gap:20px;
            border: 4px solid #385623;
        }
        .inner-border h3{
            text-align: center;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .inner-border h1{
            font-size: 40px;
        }

        .inner-border h2{
            font-size: 32px;
            /* margin-bottom: 20px; */
        }

        .print-header{
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .input-container{
            display:grid;
            grid-template-columns: 1fr 1fr;
        }

        table {
            background-color: transparent;
            table-layout : fixed;
            width: 100%;
        }

        label, p{
            font-size: 20px;
        }
        td{
            font-size: 23px;
            padding: 3px 10px;

            border: 2px solid #70ad47;
            border-collapse: collapse;
        }

        span{
            padding: 20px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            color: red;
            background-color: #e2efd9;
        }

        img{
            width: 280px;
            margin: 40px 0 20px;
        }

        .confirmation-field{
            margin: 75px 0 25px;
            border-top: 2px solid;
        }

        .signatories{
            display: flex;
            flex-direction: column;
            gap: 20px;
            position: relative;
        }

        .signatories img{
            position: absolute;
            top: -30px;
            left: 10px;
            width: 120px;
        }

        .home-btn{
            font-family: 'Poppins' !important;
            font-weight: 500;
        }

        @media print {
            .print-action{
                display: none !important;
            }
            body{
                padding: 0 !important;
                margin-top: -20px !important;
                background-color: white !important;
            }

            .outer-border{
                border: 1px solid #385623 !important;
            }
            .inner-border{
                border: 2px solid #385623 !important;
                gap: 10px;
            }

            img{
                width: 180px;
                margin: 10px 0 20px;
            }
            
            .inner-border h3{
                font-size: 17px;
            }
            .inner-border h1{
                font-size: 25px;
            }

            .inner-border h2{
                font-size: 22px;
            }

            td{
                font-size: 15px;
                padding: 0 5px !important;
                border: 3px solid #70ad47;
            }

            .confirmation-field{
                margin: 35px 0 15px;
                border-top: 1px solid;
            }

            .signatories{
                gap: 10px;
            }

            label, p{
                font-size: 14px;
            }

            span{
                font-size: 8px;
            }

            .signatories img{
                top: -10px;
                width: 90px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="outer-border p-[3px] relative">
        <div class="print-action absolute right-[-190px] top-[-35px] w-32 bg-white text-black shadow-lg">
            <ul class="py-2">
                <li>
                    <div class="home-btn text-left px-4 py-2 userselect-none cursor-pointer text-gray-500 text-[17px] hover:text-gray-800 hover:scale-105  hover:bg-gray-100 transition-transform duration-200" onclick="window.history.back()" >
                        <i class="fa-solid fa-arrow-right-to-bracket rotate-180 ml-1"></i>
                        Back
                    </div>
                </li>
                <li>
                    <div class="home-btn text-left px-4 py-2 userselect-none cursor-pointer text-gray-500 text-[17px] hover:text-gray-800 hover:scale-105  hover:bg-gray-100 transition-transform duration-200" onclick="window.print()" >
                        <i class="fa-solid fa-print"></i>
                        Print
                    </div>
                </li>
            </ul>
        </div>
        <div class="inner-border px-3 py-5 flex flex-col items-center">
            
            <div class="print-header">
                <img src="{{asset('images/BGC.png')}}" alt="">
                <h3 class="font-courier">
                    BROOKDALE FARMS CORPORATION <br>
                    Anupul, Bamban, Tarlac 
                </h3>
                <h1 class="font-courier">NOTICE OF PERSONNEL ACTION</h1>
                <h2 class="font-courier text-[#70ad47]">WAGE ORDER NO. RBIII-24</h2>                
            </div>


            <table>
                <tr>
                    <td>Name: {{$requestForm->employee_name}}</td>
                    <td>Employee No: {{$requestForm->employee_id}}</td>
                </tr>
                <tr>
                    <td>Date Hired: {{$panForm->date_hired->format('m/d/Y')}}</td>
                    <td>Division: {{$panForm->division}}</td>
                </tr>
                <tr>
                    <td>Employment Status: {{$panForm->employment_status}}</td>
                    <td>Date of Effectivity: {{$panForm->date_of_effectivity->format('m/d/Y')}}</td>
                </tr>
            </table>


            <table class="text-center">
                <tr>
                    <td class="!bg-[#e2efd9]">FROM</td>
                    <td class="!bg-[#e2efd9]">ACTION REFERENCE</td>
                    <td class="!bg-[#e2efd9]">TO</td>
                </tr>
                @foreach($panForm->action_reference_data as $item)
                    @php
                        $labels = [
                            'place'    => 'Place of Assignment',
                            'head'     => 'Immediate Head',
                            'joblevel' => 'Job Level',
                        ];
                    @endphp

                    <tr>
                        <td>{{$item['from']}}</td>
                        <td class="!bg-[#e2efd9] capitalize">{{ $labels[$item['field']] ?? $item['field'] }}</td>
                        <td>{{$item['to']}}</td>
                    </tr>

                @endforeach
            </table>


            <table class="text-center">
                <tr>
                    <td class="!bg-[#e2efd9]">REMARKS AND OTHER CONSIDERATION</td>
                </tr>
                <tr>
                    <td>
                        <input class="w-full outline-non border-none focus:ring-0 text-center" type="text" value="{{$panForm->remarks ?? 'No Remarks'}}">
                    </td>
                </tr>
            </table>


            <table class="text-center">
                <tr>
                    <td class="!bg-[#e2efd9]">CONFIRMATION OF APPOINTMENT</td>
                </tr>
                <tr>
                    <td class="flex items-center justify-around ">
                        <div class="confirmation-field">(SIGNATURE OVER PRINTED NAME)</div>
                        <div class="confirmation-field">(DATE RECIEVE)</div>
                    </td>
                </tr>
            </table>


            <div class="grid grid-cols-3 w-full px-6">
                <div class="signatories">
                    <label>Prepared By:</label>
                    <div>
                        <img src="{{asset('images/DummySignature.png')}}" alt="">
                        <p>{{$panForm->prepared_by}}</p>
                        <p>Head, Human Resources</p>
                    </div>
                </div>
                <div class="signatories">
                    <label>Recommended By:</label>
                    <div>
                        <img src="{{asset('images/DummySignature.png')}}" alt="">
                        <p>{{$requestForm->requested_by}}</p>
                        <p>Poultry Division Head</p>
                    </div>
                </div>
                <div class="signatories">
                    <label>Approved By:</label>
                    <div>
                        <img src="{{asset('images/DummySignature.png')}}" alt="">
                        <p>Atty. {{$panForm->approved_by}}</p>
                        <p>Vice President</p>
                    </div>
                </div>
            </div>


            <span>
                “Disclosing these confidential records to unauthorized personnel is punishable with Termination under Code of Discipline Section IV
                No. 4.15 Betrayal of company’s trust and confidence Unauthorized disclosure of restricted company information such as but not limited to
                development plans, budgets, details of finances and marketing strategies, test questionnaires and records, voluntarily and willingly to outsiders,
                competitors and/or those who are not authorized to possess such information.”
            </span>

        </div>
    </div>
@endsection