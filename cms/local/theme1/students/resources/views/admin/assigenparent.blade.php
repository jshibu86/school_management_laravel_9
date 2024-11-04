<style>
    .student__content{
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 5px;
    }
</style>

<div class="student__content">
    <input type="hidden" name="student_id" value="{{ @$student->id }}"/>
    <div class="img__content">
        <img src="{{ @$student->photo() }}" width=80 alt="student_image"/>
    </div>
    <div class="profile">
       <h4>{{ @$student->username }}</h4>
          
       
    </div>
</div>