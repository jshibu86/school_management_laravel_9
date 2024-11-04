
<html>
<head>
   
    <style>
       .cards-container {
            width: 100%;
        }

        .card {
            width: 46%; /* Adjust the width to fit 6 cards in a row */
            /* margin-bottom: 20px;
            border: 1px solid #ccc; */
            /* padding: 10px; */
            display: inline-block;
            box-sizing: border-box;
            vertical-align: top;
            border: 1px solid #ccc;
            height: 250px;
            margin-bottom: 30px;
        }
        .id-card {
			
			background-color: #fff;
			padding: 10px;
			border-radius: 10px;
			text-align: center;
			
		}
		.id-card img {
			margin: 0 auto;
		}
		.header img {
			width: 100px;
    		margin-top: 15px;
		}
		.photo img {
			width: 80px;
    		margin-top: 15px;
		}
		h2 {
			font-size: 15px;
			margin: 5px 0;
		}
		h3 {
			font-size: 12px;
			margin: 2.5px 0;
			font-weight: 300;
		}
		
		
        .row{
            /* display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px; */
            width: 100%;
            float: left;
        }
        .col-md-4{
            width: 33.3%;
            float: left;
        }
        .sign{
            text-align: right;
        }
        .bus__pass{
        background-color: #22a5de;
        color: white;
        font-weight: bold;
        text-align: center;
        padding: 7px;
        }
        .information h2{
         margin-bottom: 10px;
        }
        .page-break {
                page-break-after: always;
        }
        @media all {
            .page-break {
                display: none;
            }
        }

        @media print {
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <div class="cards-container">
        @for ($i=0;$i<=25;$i++)
       
        
            
            <div class="page-break"></div>
           
             <div class="card">
                <!-- Your card content goes here -->
               <div class="id-card-holder">
                    <div class="id-card">
                        <div class="header">
                            <img src="https://lh3.googleusercontent.com/-ebxWAGWvWg0/WTABBfdBv2I/AAAAAAAAAqw/qef78bVeIngorIsmAUD4tWVUd8WDvZyuQCEw/w140-h74-p/Untitled-2.png">
                        </div>

                        <div class="information">
                        <h2>Kiran Das DA</h2>
                        <h2>Father Name : Name</h2>
                        <h2>Phone Number : Name</h2>
                        <h2>Route : Name</h2>
                        </div>
                        
                        

                        <div class="sign">
                            <p>Principal Sign</p>
                        </div>
                        
                        

                    </div>
	            </div>
                <div class="bus__pass">BUS PASS </div>
            </div>
            
        @endfor
           
       
    </div>
</body>
</html>