{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-2" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #f7f8fa;border-color: #f7f8fa">
        <h5 class="modal-title py-1 px-3" style="color: #123466">
          {{-- Title --}}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">
                  &times;
              </span>
        </button>
      </div>

      <form id="deleteForm" method="POST" action="">
        @csrf
        {{ method_field('DELETE') }}

        <div class="modal-body">

          <div class="py-0 px-5">
            <div class="form-group row">
              <div class="col-1 pl-0 row">
                <i class="fa fa-exclamation-triangle fa-2x align-self-center" style="color: indianred;"></i>
              </div>
              <div class="col-11 pl-4">
                <div class="delete-info"></div>
                <div>Delete <span class="delete-model"></span> "<b class="delete-model-title"></b>" ?</div>
              </div>
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-metal" data-dismiss="modal">
            Close
          </button>
          <button type="submit" class="btn btn-primary">
            {{-- Action --}}
          </button>
        </div>

      </form>

    </div>
  </div>
</div>