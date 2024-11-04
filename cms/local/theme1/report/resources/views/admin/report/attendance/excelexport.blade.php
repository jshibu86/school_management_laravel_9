<table>
    <thead>
        <tr>
            <th>Student Name</th>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8</th>
            <th>9</th>
            <th>10</th>
            <th>11</th>
            <th>12</th>
            <th>13</th>
            <th>14</th>
            <th>15</th>
            <th>16</th>
            <th>17</th>
            <th>18</th>
            <th>19</th>
            <th>20</th>
            <th>21</th>
            <th>22</th>
            <th>23</th>
            <th>24</th>
            <th>25</th>
            <th>26</th>
            <th>27</th>
            <th>28</th>
            <th>29</th>
            <th>30</th>
            <th>31</th>
        </tr>
    </thead>
    <tbody>
        @foreach (@$calender as $student_id => $attendance)
            <tr>
                <td>{{ Configurations::GetStudent($student_id) }}</td>
                @foreach ($attendance as $month => $dates)
                    @if ($dates['is_weekend'] == 0)
                        @if (isset($dates['attendance']) && sizeof($dates['attendance']))
                            @if ($dates['attendance'][@$student_id]['present'] == 0)
                                <td class="absent">A</td>
                            @elseif ($dates['attendance'][@$student_id]['present'] == 1)
                                <td class="present">P</td>
                            @else
                                <td class="late">L</td>
                            @endif
                        @else
                            <td class="ini-bg-secondary">N/A</td>
                        @endif
                    @else
                        @if (isset($dates['attendance']) && sizeof($dates['attendance']))
                            @if ($dates['attendance'][@$student_id]['present'] == 0)
                                <td class="absent">W/A</td>
                            @elseif ($dates['attendance'][@$student_id]['present'] == 1)
                                <td class="present">W/P</td>
                            @else
                                <td class="late">W/L</td>
                            @endif
                        @else
                            <td class="weekend">W</td>
                        @endif
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>

</table>
