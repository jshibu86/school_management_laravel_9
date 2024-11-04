<style>
  td span{
    font-size: 8px !important;
  }

  td a{
    font-size: 12px!important;
  }
</style>

<div class="row">


<div class="col-12 col-lg-12 col-xl-12 d-flex">
    <div class="card_ radius-15 w-100">
      <div class="card-body_">
        <div class="d-lg-flex align-items-center mb-4">
          <div>
            <h5 class="mb-0">{{ @$subject_name }}</h5>
          </div>
          <div class="ms-auto">
            <h3 class="mb-0">
              <span class="font-14">Total Homework:</span>{{ count(@$homework_lists) }}
            </h3>
          </div>
        </div>
        <hr />
        <div class="dashboard-social-list">
            <div class="card_ radius-15 w-100">
                <div class="card-body_">
                 
                 
                  <div class="table-responsive">
                    <table class="table table-striped mb-0">
                      <thead>
                        <tr>
                          <th>Title</th>
                          <th>Submission Date</th>
                          <th>Status</th>
                          
                          <th>Action</th>
                          <th>Remark</th>
                        </tr>
                      </thead>
                      <tbody>

                        @forelse ( @$homework_lists as $homework )
                        <tr>
                            <td>{{ @$homework->title }}</td>
                            <td>{{ @$homework ->submission_date  }}</td>
                             @if (@$homework->submissions !=null)
                              @if (@$homework->submissions->evaluated == 0)
                              <td><span class="badge bg-primary">Evaluating</span></td>

                              @else
                              <td><span class="badge bg-success">Completed</span></td>
                              @endif
                             

                              <td><span class="badge bg-success">Submitted</span>
                              </td>

                              <td>{{ @$homework->submissions->teacher_remark }}</td>

                             
                             
                            @else
                            <td><span class="badge bg-rose">Pending</span>
                            </td>
                            
                            <td>

                              <a href="{{ route("homeworksubmissions",@$homework->id )}}" class="btn btn-dark btn-sm" target="_blank">Submit</a>
                           </td>
                           <td></td>
                           
                            @endif
                           
                          </tr>
                        @empty
                        <tr>
                            <td></td>
                            <td></td>
                            <td>No data Found</td>
                            <td></td>
                          </tr>
                        @endforelse
                       
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
        
        </div>
      </div>
    </div>
</div></div>