<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PANDA System</title>
    <link rel="icon" href="{{asset('/images/PANDA.ico')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap');
        
        *{
            font-family: "Lexend";
            box-sizing: border-box;
            margin: 0;
            color: #4C4C4C;
            transition: 0.3s ease;
        }

        body{
            background-color: #F1F2F6;
            height: 100vh;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            gap: 40px;

            padding-bottom: 30px;

            position: relative;
        }

        #logo{
            width: clamp(200px, 20vw, 300px);
        }


        .row {
            display: grid;
            grid-template-columns: repeat(3, 320px);
            justify-content: center;
            gap: 40px;
            max-width: 100%;
        }

        /* 5 cards: 3 on top, 2 centered below */
        .row:has(.card:nth-child(5):last-child) {
            grid-template-columns: repeat(3, 320px);
        }

        .row:has(.card:nth-child(5):last-child) .card:nth-child(4),
        .row:has(.card:nth-child(5):last-child) .card:nth-child(5) {
            grid-column: span 1;
        }

        .row:has(.card:nth-child(5):last-child) .card:nth-child(4) {
            grid-column-start: 1;
            margin-left: auto;
            margin-right: 20px;
        }

        .row:has(.card:nth-child(5):last-child) .card:nth-child(5) {
            grid-column-start: 3;
            margin-right: auto;
            margin-left: 20px;
        }

        /* 4 cards: 2x2 grid */
        .row:has(.card:nth-child(4):last-child) {
            grid-template-columns: repeat(2, 320px);
        }

        /* 3 cards: all in one row */
        .row:has(.card:nth-child(3):last-child):not(:has(.card:nth-child(4))) {
            grid-template-columns: repeat(3, 320px);
        }

        /* 2 cards: side by side */
        .row:has(.card:nth-child(2):last-child):not(:has(.card:nth-child(3))) {
            grid-template-columns: repeat(2, 320px);
        }

        /* 1 card: centered */
        .row:has(.card:only-child) {
            grid-template-columns: 320px;
        }

        @media(max-width: 1112px) {
            body {
                padding: 50px 0;
                height: auto;
            }
            
            .row {
                grid-template-columns: repeat(auto-fit, minmax(280px, 320px));
                padding: 0 20px;
            }
            
            /* Reset custom positioning on mobile */
            .row:has(.card:nth-child(5):last-child) .card:nth-child(4),
            .row:has(.card:nth-child(5):last-child) .card:nth-child(5) {
                margin-left: 0;
                margin-right: 0;
                grid-column: auto;
            }
        }

        .card {
            width: 320px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;

            background-color: white;
            padding: 30px;
            border-radius: 10px;

            text-align: center;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
        }

        .card h3 {
            font-size: clamp(14px, 3vw, 17px);
            font-weight: 700;
        }

        .card img {
            width: clamp(70px, 10vw, 110px);
        }

        .card button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;

            color: white;
            background-color: oklch(.488 .243 264.376);
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 1px;
            margin-top: 15px;
            cursor: pointer;
        }

        .userpriv-btn{
            position: absolute;
            top: 40px;
            right: 35px;
            font-size: 20px;
            cursor: pointer;
            color: #4C4C4C;
            transition: 0.4 ease;
        }.userpriv-btn:hover{
            transform:scale(1.1);
        }

        .logout-btn{
            position: absolute;
            top: 35px;
            right: 70px;
            font-size: 20px;
            cursor: pointer;
            color: #4C4C4C;
            transition: 0.4 ease;
        }.logout-btn:hover{
            transform:scale(1.1);
        }

        @media(max-width: 679px){
            .card{
                width: calc(100% - 80px);
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <a class="logout-btn" href="/logout"><i class="fa-solid fa-arrow-right-from-bracket" style="transform: rotate(180deg);"></i> Logout</a>
    <i class="fa-solid fa-gear userpriv-btn" onclick="window.location.href='/admin'"></i>


    <img src="{{asset('/images/BGC.png')}}" id="logo" alt="">
    <div class="row">
        @if(Auth()->user()->access['RQ_Module'] == true)
            <div class="card">
                <img src="{{asset('/images/RQ.png')}}" alt="">
                <h3>REQUESTOR <br> MODULE</h3>
                <button onclick="window.location.href='/requestor'">OPEN</button>
            </div>
        @endif

        @if(Auth()->user()->access['DH_Module'] == true)
            <div class="card">
                <img src="{{asset('/images/DH.png')}}" alt="">
                <h3>DIVISION HEAD <br> MODULE</h3>
                <button onclick="window.location.href='/divisionhead'">OPEN</button>
            </div>
        @endif

        @if(Auth()->user()->access['HRP_Module'] == true)
            <div class="card">
                <img src="{{asset('/images/HRP.png')}}" alt="">
                <h3>HR PREPARER <br> MODULE</h3>
                <button onclick="window.location.href='/hrpreparer'">OPEN</button>
            </div>
        @endif

        @if(Auth()->user()->access['HRA_Module'] == true)
            <div class="card">
                <img src="{{asset('/images/HRA.png')}}" alt="">
                <h3>HR APPROVER <br> MODULE</h3>
                <button onclick="window.location.href='/hrapprover'">OPEN</button>
            </div>
        @endif

        @if(Auth()->user()->access['FA_Module'] == true)
            <div class="card">
                <img src="{{asset('/images/FA.png')}}" alt="">
                <h3>FINAL APPROVER <br> MODULE</h3>
                <button onclick="window.location.href='/approver'">OPEN</button>
            </div>
        @endif
    </div>

</body>
</html>
        