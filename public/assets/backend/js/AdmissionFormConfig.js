export default class AdmissionFormConfig {
    static AdmissionFormInit(notify_script, type = null) {
        $('input[type="checkbox"]').change(function() {
            var checkbox = $(this);        
            var itemId = checkbox.data('id');
            var is_active = checkbox.is(':checked');
            // alert('Checkbox: '+checkbox);
            // alert('item ID : '+itemId);
            // alert('is Active : '+is_active);   
            console.log("active",is_active);     
             let url = window.update_status + "?itemid=" + itemId + "&is_active=" + is_active;
            axios.get(url)
            .then(function (response) {
                if(response.data.id){
                    notify_script(
                        "Success",
                        "Active status changed Successfully",
                        "success",
                        true
                    );
                }
                else{
                    console.log("response not found");
                }
                
            })
            .catch(function (error) {
                if (error.response) {
                    console.error('Error response:', error.response.data);
                } else {
                    console.error('There was an error!', error);
                }
            });
          });
}
}