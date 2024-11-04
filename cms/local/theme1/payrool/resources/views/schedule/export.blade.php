<table class="table">
    <tr>
        <td>Sl.No</td>
        <td>Staff Name</td>
        <td>No of Working Days</td>
        <td>Absent Days</td>
        <td>Due Salery(Daily)</td>
        <td>Basic Salery</td>
        <td>Annual Basic Salery</td>
        <td>CRA Basic</td>
        <td>20 % Annual Income</td>
        <td>GPS 8% Employee</td>
        <td>GPS 10% Employee</td>
        <td>GPS Total</td>
        <td>HMO</td>
        <td>Total CRA</td>
        <td>Chargable Income</td>
        <td>First 300</td>
        <td>7%</td>
        <td>Second 300</td>
        <td>11%</td>
        <td>Next 500</td>
        <td>15%</td>
        <td>Paye</td>
    </tr>
    <tbody>
        @foreach ($schedule_data as $schedule)
        <tr>
            <td>{{$loop->index+1}}</td>
            <td>{{$schedule->name}}</td>
            <td>{{$schedule->working_days}}</td>
            <td>{{$schedule->absent_days}}</td>
            <td>{{$schedule->due_salery}}</td>
            <td>{{$schedule->basic_salery}}</td>
            <td>{{$schedule->annual_basic_salery}}</td>
            <td>{{$schedule->cra_basic}}</td>
            <td>{{$schedule->annual_income_twenty_per}}</td>
            <td>{{$schedule->gps_employee_eight_per}}</td>
            <td>{{$schedule->gps_employee_ten_per}}</td>
            <td>{{$schedule->gps_total}}</td>
            <td>{{$schedule->hmo}}</td>
            <td>{{$schedule->total_cra}}</td>
            <td>{{$schedule->chargable_income}}</td>
            <td>{{$schedule->first_three_hundred}}</td>
            <td>{{$schedule->seven_per}}</td>
            <td>{{$schedule->second_three_hundred}}</td>
            <td>{{$schedule->eleven_per}}</td>
            <td>{{$schedule->next_five_hundred}}</td>
            <td>{{$schedule->fifteen_per}}</td>
            <td>{{$schedule->payee}}</td>
        </tr>
        @endforeach
        
    </tbody>
</table>