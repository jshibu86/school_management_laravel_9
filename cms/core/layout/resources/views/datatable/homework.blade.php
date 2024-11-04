@if(isset($url))
<a class="btn btn-primary btn-sm badge p-2" style='width:80px;' href="{{ @$url }}">
  Check
</a> 
@else
<p class='badge bg-danger p-2' style='width:80px;'>
  {{$text}}
</p> 
@endif