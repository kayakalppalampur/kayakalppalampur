<?php

// Setup the $row variable if it's not set but intended
if(isset($user)){
    $row = $user;
} elseif(isset($blog)){
    $row = $blog;
}

?>
@foreach($departments as $department)
    <div class="inline field">
        <div class="ui slider checkbox">
            <input
            <?php
                if(isset($row)){
                    if($row->hasDepartment($department->id)) {
                        echo "checked='checked' ";
                    }
                }
            ?>
            type="radio" class="department_checkbox" name="department_id" value="{{ $department->id }}" tabindex="0" class="hidden">
            <label>{{ $department->title }}
                @if($department->su)<i class="red asterisk thin icon pop" data-variation="wide" data-position="right center" data-title="{{ trans('laralum.su_department') }}" data-content="{{ trans('laralum.su_department_desc') }}"></i>@endif
            </label>
        </div>
    </div>
@endforeach
<script>

</script>
