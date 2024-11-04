<style>
   
    .feedback{
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 50%;
    margin-top: 20px;
    }
    .print_btn{
        text-align: right;
    }
    /* .container{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    } */
    section {
	margin: 0;
	box-sizing: border-box;
	color: #000;
	font-size: 14px;
	font-weight: 400;
}
h1, h2, h3, h4, h5, h6, p
{
	margin: 0;
}
h1
{
	font-size: 30px;
}
h4,h6
{
	font-size: 15px;
	font-weight: 400;
}
table, th, td
{
	padding: 10px;
}
td
{
	width: 25%;
	padding: 10px 20px;
}
thead
{
	border: 1px solid #000;
}
.logoimg
{
	width: 80%;
	height: auto;
}
.table_container
{
	max-width: 800px;
	min-width: 800px;
	border-collapse: collapse;
	border: 1px solid #000;
	margin: 40px auto;
}
.mainhead
{
	border: 1px solid #000;
	display: inline-block;
	padding: 10px 15px;
	font-size: 25px;
}
.s_details{
    text-align: center;
}

</style>
<style type="text/css" media="print">
	@page {
		size: auto;   /* auto is the initial value */
		margin: 0;  /* this affects the margin in the printer settings */
	}
    
	</style>


<section>
		
    <table class="table_container" align="center">
        <thead>
            <tr>
                <th>
                    @if(isset(Configurations::getConfig('site')->imagec))
                    <img src="{{ Configurations::getConfig('site')->imagec }} " class="logoimg"/>
                    
                    @endif
                   
                </th>
               
                <th align="left" colspan="2">
                    <div class="s_details">
                        <h1>{{ Configurations::getConfig('site')->school_name }}</h1>
                        <h4>{{ Configurations::getConfig('site')->place }} , {{ Configurations::getConfig('site')->city }} , {{ Configurations::getConfig('site')->post }}</h4>
                        <h4><strong>Email: </strong>{{ Configurations::getConfig('site')->school_email }}</h4>
                        <h4><strong>Phone: </strong>{{ Configurations::getConfig('site')->school_landline }}</h4>
                    </div>
                </th>
                <th>
                   
                    <img src="{{ @$user->images ?  @$user->images : "/assets/images/default.jpg" }}" width=80 alt="student_image" class="logoimg"/>
                    
                   
                   
                </th>
            </tr>
        </thead>
        <tbody class="body_text">
            <tr>
                <td colspan="4" align="center">
                    <h1 class="mainhead">Leave Application</h1>
                </td>
            </tr>
            <tr>
                <td><h6><strong>Name: </strong></h6></td>
                <td><h6>{{ @$user->name }}</h6></td>
                <td><h6><strong>Email: </strong></h6></td>
                <td><h6>{{ @$user->email }}</h6></td>
            </tr>
            <tr>
                <td><h6><strong>Phone Number :</strong></h6></td>
                <td><h6>{{ @$user->mobile }}</h6></td>
                <td><h6><strong>User Name :</strong></h6></td>
                <td><h6>{{ @$user->username }}</h6></td>
            </tr>
            <tr>
                <td><h6><strong>From</strong></h6></td>
                <td><h6>{{  \Carbon\Carbon::parse($leave_data->from_date)->format(
                    "Y-m-d"
                ); }}</h6></td>
                <td><h6><strong>To</strong></h6></td>
                <td><h6>{{  \Carbon\Carbon::parse($leave_data->to_date)->format(
                    "Y-m-d"
                ); }}</h6></td>
                
            </tr>
            <tr>
                <td><h6><strong>Reason :</strong></h6></td>
                <td colspan="3"><h6>{{ @$leave_data->reason }}</h6></td>
            </tr>
        </tbody>
    </table>

</section>




<script>
    window.addEventListener('load', (event) => {
 window.print();
});
  </script>




