@extends('layouts.app')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        *{
            font-family: 'Courier' !important; 
            font-weight: 700;
        }
        .outer-border{
            margin: 20px;
            background-color: white;
            outline: 35px solid white;
        }
        .inner-border h1{
            font-size: 40px;
        }

        .inner-border h2{
            font-size: 32px;
        }
        .inner-border h3{
            text-align: center;
            font-size: 28px;
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

        td{
            font-size: 23px;
            padding: 3px 10px;

            border: 2px solid #70ad47;
            border-collapse: collapse;
        }
    </style>
    <div class="outer-border border-solid border-2 p-[3px] border-[#385623]">
        <div class="inner-border border-solid border-3 px-2 py-5 flex flex-col items-center border-[#385623]">
            
            <img src="{{asset('images/BDL.png')}}" alt="" style="width: 280px">
            <h3 class="font-courier">
                BROOKDALE FARMS CORPORATION <br>
                Anupul, Bamban, Tarlac
            </h3>
            <h1 class="font-courier">NOTICE OF PERSONNEL ACTION</h1>
            <h2 class="font-courier text-[#70ad47]">WAGE ORDER NO. RBIII-24</h2>

            <table>
                <tr>
                    <td>Name: </td>
                    <td>Employee No: </td>
                </tr>
                <tr>
                    <td>Date Hired: </td>
                    <td>Division: </td>
                </tr>
                <tr>
                    <td>Employment Status: </td>
                    <td>Date of Effectivity: </td>
                </tr>
            </table>

            <br>

            <table class="text-center">
                <tr class="!bg-[#e2efd9]">
                    <td>FROM</td>
                    <td>ACTION REFERENCE</td>
                    <td>TO</td>
                </tr>
                <tr>
                    <td></td>
                    <td>ACTION REFERENCE</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>ACTION REFERENCE</td>
                    <td></td>
                </tr>
                
            </table>
        </div>
    </div>
@endsection