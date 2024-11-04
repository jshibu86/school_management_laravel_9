@php
    $theme=Configurations::getCurrentTheme();
@endphp
@extends('layout::admin.master')

@section('title','')

@section('body')
<div class="row">

  @if (Session::get("ACTIVE_GROUP") == "Student")
  <div class="col-12 col-lg-4">
    <div class="card radius-15 overflow-hidden">
      <div class="card-body">
        <div class="d-flex">
          <div>
            <p class="mb-0 font-weight-bold">Homeworks</p>
            <h2 class="mb-0">{{ @$homeworks }}</h2>
          </div>
          <div class="ms-auto align-self-end">
            <p class="mb-0 font-14 text-primary">
              <i class="bx bxs-up-arrow-circle"></i>
              <span></span>
            </p>
          </div>
        </div>
        <div id="chart1"></div>
      </div>
    </div>
  </div>
  
  @endif
  @if (Session::get("ACTIVE_GROUP") == "Super Admin")
 
    <div class="col-12 col-lg-4">
      <div class="card radius-15 overflow-hidden">
        <div class="card-body">
          <div class="d-flex">
            <div>
              <p class="mb-0 font-weight-bold">Students</p>
              <h2 class="mb-0">{{ @$stucount }}</h2>
            </div>
            <div class="ms-auto align-self-end">
              <p class="mb-0 font-14 text-primary">
                <i class="bx bxs-up-arrow-circle"></i>
                <span>1.01% 31 days ago</span>
              </p>
            </div>
          </div>
          <div id="chart1"></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-4">
      <div class="card radius-15 overflow-hidden">
        <div class="card-body">
          <div class="d-flex">
            <div>
              <p class="mb-0 font-weight-bold">Staff's</p>
              <h2 class="mb-0">{{ @$staffcount }}</h2>
            </div>
            <div class="ms-auto align-self-end">
              <p class="mb-0 font-14 text-success">
                <i class="bx bxs-up-arrow-circle"></i>
                <span>0.49% 31 days ago</span>
              </p>
            </div>
          </div>
          <div id="chart2"></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-4">
      <div class="card radius-15 overflow-hidden">
        <div class="card-body">
          <div class="d-flex">
            <div>
              <p class="mb-0 font-weight-bold">Parents</p>
              <h2 class="mb-0">{{ @$parentcount }}</h2>
            </div>
            <div class="ms-auto align-self-end">
              <p class="mb-0 font-14 text-danger">
                <i class="bx bxs-down-arrow-circle"></i>
                <span>130.68% 31 days ago</span>
              </p>
            </div>
          </div>
          <div id="chart3"></div>
        </div>
      </div>
    </div>
  </div>
@endif
  
@endsection

@section("scripts")
<script>
  window.dummyjson="from main blade";
</script>
@endsection