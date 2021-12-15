@if($neuro->id != null)
    <div class="table_head_lft">
        <table id="w-comman" class="ui table table_cus_v bs">
            <tbody>
            <tr>
                <th colspan="6"><h5>Neurological System</h5></th>
            </tr>
            <tr>
                @if($neuro->headache_doctor || $neuro->getValue('headache'))
                    <th>Headache</th>
                    <td style="border-top: 1px solid #ddd">{{ $neuro->getValue('headache') }}</td>
                    <td style="border-top: 1px solid #ddd">{{ $neuro->headache_doctor}}</td>
                @endif
                @if($neuro->vision_hearing_doctor || $neuro->getValue('vision_hearing'))
                    <th style="border-top: 1px solid #ddd">Problem with vision/ hearing etc.</th>
                    <td style="border-top: 1px solid #ddd">{{ $neuro->getValue('vision_hearing') }}</td>
                    <td style="border-top: 1px solid #ddd">{{ $neuro->vision_hearing_doctor}}</td>
                @endif
            </tr>
            <tr>
                @if($neuro->pain_doctor || $neuro->getValue('pain'))
                    <th>Pain.</th>
                    <td>{{ $neuro->getValue('pain') }}</td>
                    <td>{{ $neuro->pain_doctor }}</td>
                @endif
                @if($neuro->numbness_doctor || $neuro->getValue('numbness'))
                    <th>Numbness/ Pins& Needles</th>
                    <td>{{ $neuro->getValue('numbness') }}</td>
                    <td>{{ $neuro->numbness_doctor }}</td>
                @endif
            </tr>
            <tr>
                @if($neuro->weakness_doctor || $neuro->getValue('weakness'))
                    <th>Weakness or balance problem</th>
                    <td>{{ $neuro->getValue('weakness') }}</td>
                    <td>{{ $neuro->weakness_doctor }}</td>
                @endif
                @if($neuro->abnormal_movements_doctor || $neuro->getValue('abnormal_movements'))
                    <th>Abnormal/ involuntary movements</th>
                    <td>{{ $neuro->getValue('abnormal_movements') }}</td>
                    <td>{{ $neuro->abnormal_movements_doctor }}</td>
                @endif
            </tr>
            <tr>
                @if($neuro->fits_doctor || $neuro->getValue('fits'))
                    <th>Fits/ faints</th>
                    <td>{{ $neuro->getValue('fits') }}</td>
                    <td style="border-right:1px solid #ddd">{{ $neuro->fits_doctor }}</td>
                @endif

                @if($neuro->doctor_details || $neuro->getValue('doctor_details'))
                    <th>On Examination</th>
                    <td></td>
                    <td style="border-right:1px solid #ddd">{{ $neuro->doctor_details }}</td>
                @endif

            </tr>
            </tbody>
        </table>
    </div>
@endif


@if($skin->id != null)
<div class="table_head_lft">
        <table id="w-comman" class="ui table table_cus_v bs">
            <tbody>
            <tr>
                <th colspan="2"><h5>Skin Examination</h5></th>
            </tr>
            <tr>
                @if($skin->skin)
                    <th>Skin</th>
                    <td style="border-top: 1px solid #ddd">{{ $skin->skin}}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
    
@endif

@if($eye->id != null)
<div class="table_head_lft">
        <table id="w-comman" class="ui table table_cus_v bs">
            <tbody>
            <tr>
                <th colspan=2><h5>Eye Examination</h5></th>
            </tr>
            <tr>
                @if($eye->eye_ent)
                    <th>Eye/Ent</th>
                    <td style="border-top: 1px solid #ddd">{{ $eye->eye_ent}}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
    
@endif

@if($systemic->id != null)
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>

            <!-- <tr>
                <h3 style="border-top: 1px solid #ddd; border-right: 1px solid #ddd; border-left: 1px solid #ddd">
                    <center>Physiotherapy Examinations</center>
                </h3>
            </tr> -->

            <tr>
                <th colspan=10 ><h3 style="text-align: center;">Physiotherapy Examinations</h3></th>
            </tr>
            <tr>
                <th colspan=10><h5>On examination</h5></th>
            </tr>

            <tr>
                @if($systemic->getValue('body_built'))
                    <th>Body Built</th>
                    <td style="border-top: 1px solid #ddd">{{ $systemic->getValue('body_built') }}</td>
                    <td style="border-top: 1px solid #ddd"></td>
                @endif
                @if($systemic->getValue('gait'))
                    <th style="border-top: 1px solid #ddd">Gait.</th>
                    <td style="border-top: 1px solid #ddd">{{ $systemic->getValue('gait') }}</td>
                    <td style="border-top: 1px solid #ddd"></td>
                @endif
            </tr>

            <tr>
                @if($systemic->getValue('posture') || $systemic->posture_comment)
                    <th>Posture</th>
                    <td>{{ $systemic->getValue('posture') }}</td>
                    <td>{{ $systemic->posture_comment }}</td>
                @endif
                @if($systemic->getValue('deformity') || $systemic->deformity_comment)

                    <th>Deformity</th>
                    <td>{{ $systemic->getValue('deformity') }}</td>
                    <td>{{ $systemic->deformity_comment }}</td>
                @endif
            </tr>

            <tr>
                @if($systemic->getValue('tenderness') || $systemic->tenderness_comment)
                    <th>Tenderness</th>
                    <td>{{ $systemic->getValue('tenderness') }}</td>
                    <td>{{ $systemic->tenderness_comment }}</td>
                @endif
                @if($systemic->getValue('warmth') || $systemic->warmth_comment)
                    <th>Warmth</th>
                    <td>{{ $systemic->getValue('warmth') }}</td>
                    <td>{{ $systemic->warmth_comment }}</td>
                @endif
            </tr>

            <tr>
                @if($systemic->getValue('swelling') || $systemic->swelling_comment)
                    <th>Swelling</th>
                    <td>{{ $systemic->getValue('swelling') }}</td>
                    <td style="border-right:1px solid #ddd">{{ $systemic->swelling_comment  }}</td>
                @endif
                @if($systemic->getValue('creiptus') || $systemic->creiptus_comment)
                    <th>Crepitus</th>
                    <td>{{ $systemic->getValue('creiptus') }}</td>
                    <td style="border-right:1px solid #ddd">{{ $systemic->creiptus_comment  }}</td>
                @endif
            </tr>
            <tr>
                @if($systemic->getValue('muscle_spasm') || $systemic->muscle_spasm_comment)
                    <th>Muscle Spasm</th>
                    <td>{{ $systemic->getValue('muscle_spasm') }}</td>
                    <td>{{ $systemic->muscle_spasm_comment }}</td>
                @endif
                @if($systemic->getValue('muscle_tightness') || $systemic->muscle_tightness_comment)
                    <th>Muscle Tightness</th>
                    <td>{{ $systemic->getValue('muscle_tightness') }}</td>
                    <td>{{ $systemic->muscle_tightness_comment }}</td>
                @endif
            </tr>
            <tr>
                @if($systemic->getValue('edema'))
                    <th>Edema</th>
                    <td>{{ $systemic->getValue('edema') }}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
@endif
@if($sensory->id != null)
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>
            <tr>
                <th colspan=10><h5>Sensory Examination</h5></th>
            </tr>
            <tr>
                @if($sensory->getValue('superficial_sensation') || $sensory->superficial_sensation_comment)
                    <th width="30%">Superficial Sensation</th>
                    <td style="border-top: 1px solid #ddd">{{ $sensory->getValue('superficial_sensation') }}</td>
                    <td style="border-top: 1px solid #ddd">{{ $sensory->superficial_sensation_comment }}</td>
                @endif
                @if($sensory->getValue('deep_sensation') || $sensory->deep_sensation_comment)
                    <th style="border-top: 1px solid #ddd">Deep Sensation.</th>
                    <td style="border-top: 1px solid #ddd">{{ $sensory->getValue('deep_sensation') }}</td>
                    <td style="border-top: 1px solid #ddd">{{ $sensory->deep_sensation_comment }}</td>
                @endif

            </tr>
            <tr>
                @if($sensory->getValue('hot_or_cold_sensation') || $sensory->hot_or_cold_sensation_comment)
                    <th>Hot/Cold Sensation</th>
                    <td>{{ $sensory->getValue('hot_or_cold_sensation') }}</td>
                    <td>{{ $sensory->hot_or_cold_sensation_comment }}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
@endif
@if($motor->id != null)
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>
            <tr>
                <th colspan=10><h5>Motor Examination</h5></th>
            </tr>
            <tr>
                @if($sensory->getValue('rom_of_joint') || $sensory->rom_of_joint_type)
                    <th width="30%">ROM Of Joint</th>
                    <td style="border-top: 1px solid #ddd">{{ $motor->getValue('rom_of_joint') }}</td>
                    <td style="border-top: 1px solid #ddd">{{ $motor->rom_of_joint_type }}</td>
                @endif
                @if($motor->getValue('muscle_power_grade') || $motor->muscle_power_grade_comment)
                    <th style="border-top: 1px solid #ddd">Muscle Power/Grade</th>
                    <td style="border-top: 1px solid #ddd">{{ $motor->getValue('muscle_power_grade') }}</td>
                    <td style="border-top: 1px solid #ddd">{{ $motor->muscle_power_grade_comment }}</td>
                @endif

            </tr>

            <tr>
                @if($motor->getValue('muscle_power_tone') || $motor->muscle_power_tone_comment)
                    <th>Muscle Tone</th>
                    <td style="border-right:1px solid #ddd">{{ $motor->getValue('muscle_power_tone') }}</td>
                    <td style="border-right:1px solid #ddd">{{ $motor->muscle_power_tone_comment  }}</td>
                @endif
                <td style="border-right:1px solid #ddd"></td>
                <td style="border-right:1px solid #ddd"></td>
                <td style="border-right:1px solid #ddd"></td>


            </tr>

            @if(count($motor->getJoints()) > 0)
                @foreach($motor->getJoints() as $joint)
                    <tr>
                        <th>Joint (Normal ROM:{{ $joint['normal_rom'] }})</th>
                        <td style="border-right:1px solid #ddd;">{{ $joint['joint'] }}</td>
                        <th>Sub Category</th>
                        <td style="border-right:1px solid #ddd">{{ $joint['joint_sub_category'] }}</td>
                        <th>@if($joint['right'] != '') Right Side @endif @if($joint['left'] != '' && $joint['right'] != '' ) / @endif @if($joint['left'] != '') Left Side @endif</th>
                        <td style="border-right:1px solid #ddd">{{ $joint['right'] }} @if($joint['left'] != '' && $joint['right'] != '' ) / @endif {{ $joint['left'] }}
                            </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>
            <tr>
                <th colspan=10><h5>Reflexes</h5></th>
            </tr>
            <tr>
                @if($motor->getValue('deep_reflexes') || $motor->deep_reflexes_comment)
                    <th>Deep Reflexes</th>
                    <td style="border-top: 1px solid #ddd">{{ $motor->getValue('deep_reflexes') }}</td>
                    <td style="border-top: 1px solid #ddd">{{ $motor->deep_reflexes_comment }}</td>
                @endif
                @if($motor->getValue('superficial_reflexes') || $motor->superficial_reflexes_comment)
                    <th style="border-top: 1px solid #ddd">Superficial Reflexes</th>
                    <td style="border-top: 1px solid #ddd">{{ $motor->getValue('superficial_reflexes') }}</td>
                    <td style="border-top: 1px solid #ddd">{{ $motor->superficial_reflexes_comment }}</td>
                @endif
            </tr>

            <tr>
                @if($motor->getValue('bower_and_bladder') || $motor->bower_and_bladder_comment)
                    <th>Bowel & Bladder</th>
                    <td>{{ $motor->getValue('bower_and_bladder') }}</td>
                    <td style="border-right:1px solid #ddd">{{ $motor->bower_and_bladder_comment  }}</td>
                @endif
                @if($motor->specific_test)
                    <th>Specific Test, if Any</th>
                    <td>{{ $motor->specific_test }}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
@endif
@if($pain->id != null)
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>
            <tr>
                <th colspan=10><h5>Musculo Skeletal</h5></th>
            </tr>
            <tr>
                @if($pain->getValue('muscle_pain') || $pain->muscle_pain_comment)
                    <th>Muscle Pain</th>
                    <td style="border-top: 1px solid #ddd">{{ $pain->getValue('muscle_pain') }}</td>
                    <td style="border-top: 1px solid #ddd">{{ $pain->muscle_pain_comment }}</td>
                @endif
                @if($pain->getValue('back_pain') || $pain->back_pain_comment)
                    <th style="border-top: 1px solid #ddd">Back Pain</th>
                    <td style="border-top: 1px solid #ddd">{{ $pain->getValue('back_pain') }}</td>
                    <td style="border-top: 1px solid #ddd">{{ $pain->back_pain_comment }}</td>
                @endif
            </tr>

            <tr>
                @if($pain->getValue('knee_pain') || $pain->knee_pain_comment)
                    <th>Knee Pain</th>
                    <td>{{ $pain->getValue('knee_pain') }}</td>
                    <td>{{ $pain->knee_pain_comment }}</td>
                @endif
                @if($pain->getValue('joint_pain') || $pain->joint_pain_comment)
                    <th>Joint Pain</th>
                    <td>{{ $pain->getValue('joint_pain') }}</td>
                    <td>{{ $pain->joint_pain_comment }}</td>
            @endif
            <tr>
                @if($pain->getValue('spinal_injuries') || $pain->spinal_injuries_comment)
                    <th>Spinal Injuries</th>
                    <td>{{ $pain->getValue('spinal_injuries') }}</td>
                    <td>{{ $pain->spinal_injuries_comment  }}</td>
                @endif
                @if($pain->getValue('side'))
                    <th>Side</th>
                    <td style="border-right: 1px solid #ddd">{{ $pain->getValue('side') }}</td>
                @endif
            </tr>
            <tr>
                @if($pain->onset_of_symptoms)
                    <th>Onset of Symptoms</th>
                    <td>{{ $pain->onset_of_symptoms }}</td>
                @endif
                @if($pain->getValue('priorities_injuries_to_affected_area') || $pain->priorities_injuries_to_affected_area_comment)
                    <th>Prior injury To Affected Area</th>
                    <td>{{ $pain->getValue('priorities_injuries_to_affected_area') }}</td>
                    <td>{{ $pain->priorities_injuries_to_affected_area_comment }}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
@endif
@if($pain_assesment->id != null)
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>
            <tr>
                <th colspan=10><h5>Pain Assessments</h5></th>
            </tr>
            <tr>
                @if($pain_assesment->pain_at_rest)
                    <th>Pain At Rest</th>
                    <td style="border-top: 1px solid #ddd">{{ $pain_assesment->pain_at_rest }}</td>
                @endif
                @if($pain_assesment->pain_with_activity)
                    <th style="border-top: 1px solid #ddd">Pain With Activity</th>
                    <td style="border-top: 1px solid #ddd">{{ $pain_assesment->pain_with_activity }}</td>
                @endif
            </tr>

            <tr>
                @if($pain_assesment->pain_at_night)
                    <th>Pain At Night</th>
                    <td>{{ $pain_assesment->pain_at_night }}</td>
                @endif
                @if($pain_assesment->aggregation_factor)
                    <th>Aggravating Factor</th>
                    <td>{{ $pain_assesment->aggregation_factor }}</td>
            @endif
            <tr>
                @if($pain_assesment->relieving_factor)
                    <th>Relieving Factor</th>
                    <td>{{ $pain_assesment->relieving_factor }}</td>
                @endif
                @if($pain_assesment->getValue('type_of_pain') )
                    <th>Type of Pain</th>
                    <td>{{ $pain_assesment->getValue('type_of_pain') }}</td>
                @endif
            </tr>
            <tr>
                @if($pain_assesment->getValue('nature_of_pain'))
                    <th>Nature of Pain</th>
                    <td>{{ $pain_assesment->getValue('nature_of_pain') }}</td>
                @endif
                @if($pain_assesment->getValue('symptoms_are_worse'))
                    <th>Symptoms Are Worse</th>
                    <td>{{ $pain_assesment->getValue('symptoms_are_worse') }}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
@endif