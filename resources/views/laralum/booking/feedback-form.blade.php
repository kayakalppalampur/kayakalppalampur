
<div class="booking_info_page">
    <div class="content_Box">
        <div class="content_BoxIN">
            <form action="{{ url("admin/user/submit-feedback/".$booking->id) }}" id="feedback-form-data" method="POST">{{ csrf_field() }}
                @foreach(\App\FeedbackQuestion::all() as $question)
            <div class="popup-faq-wrap">
                <h3>{{ $loop->iteration }}. {{ $question->question }}?</h3>
                     <div class="pop-outer-feed">
                <div class="radio-btn-outer">
                 <p class="feed">Bad</p><input type="radio" value="0" name="rate_{{ $question->id }}" {{ $feedback->checkValue($question->id,1) ? "checked" : "" }}> <label>0</label>
  
                </div>

                <div class="radio-btn-outer">
                 <input type="radio" value="1"   {{ $feedback->checkValue($question->id,0) ? "checked" : "" }} name="rate_{{ $question->id }}" > <label>1</label>
  
                </div>

                <div class="radio-btn-outer">
                 <p class="feed">ok</p><input value="2"  type="radio" name="rate_{{ $question->id }}"  {{ $feedback->checkValue($question->id,2) ? "checked" : "" }}> <label>2</label>
  
                </div>

                <div class="radio-btn-outer">
                 <input type="radio" value="3"  name="rate_{{ $question->id }}"  {{ $feedback->checkValue($question->id,3) ? "checked" : "" }}> <label>3</label>
  
                </div>

                <div class="radio-btn-outer">
                 <p class="feed">Good</p><input type="radio" value="4"  name="rate_{{ $question->id }}"  {{ $feedback->checkValue($question->id,4) ? "checked" : "" }}> <label>4</label>
  
                </div>

                <div class="radio-btn-outer">
                 <input type="radio" name="rate_{{ $question->id }}" value="5"  {{ $feedback->checkValue($question->id,5) ? "checked" : "" }}> <label>5</label>
  
                </div>

                <div class="radio-btn-outer">
                 <p class="feed"> Very Good</p><input type="radio" value="6"  name="rate_{{ $question->id }}"  {{ $feedback->checkValue($question->id,6) ? "checked" : "" }}> <label>6</label>
  
                </div>

                <div class="radio-btn-outer">
                 <input type="radio" name="rate_{{ $question->id }}" value="7"   {{ $feedback->checkValue($question->id,7) ? "checked" : "" }}> <label>7</label>

                </div>
                 <div class="radio-btn-outer">
                 <p class="feed">Excellent</p><input type="radio" value="8"  name="rate_{{ $question->id }}"  {{ $feedback->checkValue($question->id,8) ? "checked" : "" }}> <label>8</label>
  
                </div>
                 <div class="radio-btn-outer">
                 <input type="radio" name="rate_{{ $question->id }}" value="9"   {{ $feedback->checkValue($question->id,9) ? "checked" : "" }}><label>9</label>
  
                </div>
                 <div class="radio-btn-outer">
                 <p class="feed">Satisfying</p><input type="radio" value="10"  name="rate_{{ $question->id }}"  {{ $feedback->checkValue($question->id,10) ? "checked" : "" }}> <label>10</label>
  
                </div>
                </div>
            </div>
                @endforeach
<div class="popup-faq-wrap">
                     <h3>Give us brief feedback</h3>
                <textarea name="feedback">{{ $feedback->feedback }}</textarea>
                </div>
                </form>

            <div class="form-button_row"><button id="save-feedback" class="ui button no-disable blue">Save</button></div>
        </div>
    </div>
</div>
<script>
    $("#save-feedback").click(function () {
        console.log("SDf");
        $.ajax({
            url:"{{ url("admin/user/submit-feedback/".$booking->id) }}" ,
            data:$("#feedback-form-data").serialize(),
            type:"POST",
            success:function () {
                $(".close").trigger('click');
            }
        });
    })
</script>