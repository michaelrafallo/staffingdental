
<div class="modal fade delete-modal" id="deleteModal"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
            <h4 class="modal-title uppercase">Title goes here ...</h4>
        </div>
        <div class="modal-body">
        Loading content body ..        
        </div>

        <div class="modal-footer">
        <a class="btn btn-primary confirm uppercase" data-href="#" data-index="" data-id="" data-target="">Confirm</a>
        <button class="btn btn-default uppercase" aria-hidden="true" data-dismiss="modal" class="close" type="button">Close</button> 
        <span class="msg"></span>           
        </div>
       
    </div>
  </div>
</div>




<div class="modal fade application-modal" id="deleteModal"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
            <h4 class="modal-title sbold uppercase">Apply to Job</h4>
        </div>
        <form method="post" action="" class="form-submit">
            {{ csrf_field() }}

            <div class="modal-body">
    
                <div class="modal-msg"></div>

                <div class="form-group margin-top-10">
                    <strong>Cover Letter</strong>
                    <textarea class="form-control margin-top-10" name="cover_letter" rows="8" placeholder="Enter your cover letter here">{{ App\Setting::get_setting('applicant_cover_letter') }}</textarea>
                </div>
            </div>

            <div class="modal-footer">
            <button type="submit" class="btn btn-primary confirm uppercase">Submit</button>
            <button class="btn btn-default uppercase" aria-hidden="true" data-dismiss="modal" class="close" type="button">Close</button> 
            <span class="msg"></span>           
            </div>
        </form>    
    </div>
  </div>
</div>
