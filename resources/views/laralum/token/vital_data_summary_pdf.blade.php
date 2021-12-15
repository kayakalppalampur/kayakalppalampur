@if($vitalData->id != null)
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>
            @if($vitalData->present_complaints)
                <tr>
                    <th>Present Complaints/Illness</th>
                    <td style="border-top: 1px solid #ddd; word-break: break-all;">{{ $vitalData->present_complaints }}</td>
                    
                </tr>
            @endif
            @if($vitalData->past_illness)
                <tr>
                    <th>Past Illness</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->past_illness }}</td>
                </tr>
            @endif
            @if($vitalData->family_history)
                <tr>
                    <th>Family History</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->family_history }}</td>
                </tr>
            @endif
            @if($vitalData->gynecological_obs_history)
                <tr>
                    <th>Gynecological & OBS. History</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->gynecological_obs_history }}</td>
                </tr>
            @endif
            @if($vitalData->personal_history)
                <tr>
                    <th>Personal History</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->personal_history }}</td>
                </tr>
            @endif
            @if($vitalData->diet)
                <tr>
                    <th>Diet</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->diet }}</td>
                </tr>
            @endif
            @if($vitalData->sleep)
                <tr>
                    <th>Sleep</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->sleep }}</td>
                </tr>
            @endif
            @if($vitalData->appetite)
                <tr>
                    <th>Appetite</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->appetite }}</td>
                </tr>
            @endif
            @if($vitalData->bowel)
                <tr>
                    <th>Bowel</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->bowel }}</td>
                </tr>
            @endif
            @if($vitalData->exercise)
                <tr>
                    <th>Exercise</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->exercise }}</td>
                </tr>
            @endif
            @if($vitalData->digestion)
                <tr>
                    <th>Digestion</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->digestion }}</td>
                </tr>
            @endif
            @if($vitalData->habits)
                <tr>
                    <th>Habits</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->habits }}</td>
                </tr>
            @endif
            @if($vitalData->urine)
                <tr>
                    <th>Urine</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->urine }}</td>
                </tr>
            @endif
            @if($vitalData->addiction)
                <tr>
                    <th>Addiction</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->addiction }}</td>
                </tr>
            @endif
            @if($vitalData->tongue)
                <tr>
                    <th>Tongue</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->tongue }}</td>
                </tr>
            @endif
            @if($vitalData->water_intake)
                <tr>
                    <th>Water Intake</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->water_intake }}</td>
                </tr>
            @endif
            @if($vitalData->treatment_details)
                <tr>
                    <th>Treatment & Medication</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->treatment_details }}</td>
                </tr>
            @endif
            @if($vitalData->past_investigation)
                <tr>
                    <th>Past Investigation (if any)</th>
                    <td style="border-top: 1px solid #ddd">{{ $vitalData->past_investigation }}</td>
                </tr>
            @endif

           </tbody>
        </table>
    </div>
@endif

@if($physical->id != null)
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs" style="margin-top:0;">
            <tbody>
            <tr>
                <th colspan="12" width="100%"><h5>General Physical Examinations</h5></th>
            </tr>
            <tr>
                @if($physical->built)
                    <th>Built</th>
                    <td style="border-top: 1px solid #ddd">{{ $physical->built }}</td>
                @endif

                @if($physical->heart_rate)
                    <th class="border_left">Heart Rate</th>
                    <td style="border-top: 1px solid #ddd">{{ $physical->heart_rate }}</td>
                @endif
                @if($physical->anaemia)
                    <th class="border_left">Anaemia</th>
                    <td style="border-top: 1px solid #ddd">{{ $physical->anaemia }}</td>
                @endif

                @if($physical->nourishment)
                    <th class="border_left" style="border-top: 1px solid #ddd">Nourishment</th>
                    <td style="border-top: 1px solid #ddd">{{ $physical->nourishment }}</td>
                @endif

            </tr>
            <tr>
                @if($physical->temperature)
                    <th>Temperature</th>
                    <td>{{ $physical->temperature }}</td>
                @endif
                @if($physical->respiratory_rate)
                    <th class="border_left">Respiratory Rate</th>
                    <td>{{ $physical->respiratory_rate }}</td>
                @endif
                @if($physical->icterus)
                    <th class="border_left">Icterus</th>
                    <td>{{ $physical->icterus }}</td>
                @endif
                @if($physical->cyanosis)
                    <th class="border_left">Cyanosis</th>
                    <td>{{ $physical->cyanosis }}</td>
                @endif
            </tr>
            <tr>
                @if($physical->nails)
                    <th>Nails</th>
                    <td>{{ $physical->nails }}</td>
                @endif
                @if($physical->clubbing)
                    <th class="border_left">Clubbing</th>
                    <td>{{ $physical->clubbing }}</td>
                @endif
                @if($physical->lymph_nodes_enlargement)
                    <th class="border_left">Lymph Nodes Enlargement</th>
                    <td>{{ $physical->lymph_nodes_enlargement }}</td>
                @endif
                @if($physical->oedema)
                    <th class="border_left">Oedema</th>
                    <td>{{ $physical->oedema }}</td>
                @endif  
            </tr>
            <tr>
                @if($physical->tongue)
                    <th class="border_left">Tongue</th>
                    <td style="border-right:1px solid #ddd">{{ $physical->tongue }}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
@endif

@if($respiratory->id != null || $cardio->id != null)
    <div class="table_head_lft">
        <!-- <table id="w-comman" class="ui table table_cus_v bs" style="margin: 0;">
            <tbody>
            <tr>
                <h3 style="border-top: 1px solid #ddd; border-right: 1px solid #ddd; border-left: 1px solid #ddd">
                    <center>Systematic Examinations</center>
                </h3>
            </tr>
            </tbody>
        </table> -->

        @if($cardio->id != null)
            <table id="w-comman" class="ui table table_cus_v bs" style="margin-top:0;">
                <tbody>
                <tr>
                    <th colspan="6"><h6>Cardiovascular System</h6></th>
                </tr>
                <tr>
                    @if($cardio->chest_pain_doctor || $cardio->getValue('chest_pain'))
                        <th width="30%">Chest Pain</th>
                        <td style="border-top: 1px solid #ddd">{{ $cardio->getValue('chest_pain')}}</td>
                        <td style="border-top: 1px solid #ddd">{{ $cardio->chest_pain_doctor }}</td>
                    @endif
                    @if($cardio->dyspnoea_doctor || $cardio->getValue('dyspnoea'))
                        <th style="border-top: 1px solid #ddd">Dyspnoea</th>
                        <td style="border-top: 1px solid #ddd">{{ $cardio->getValue('dyspnoea') }}</td>
                        <td style="border-top: 1px solid #ddd">{{ $cardio->dyspnoea_doctor}}</td>
                    @endif
                </tr>
                <tr>
                    @if($cardio->palpitations_doctor || $cardio->getValue('palpitations'))
                        <th>Palpitations</th>
                        <td>{{ $cardio->getValue('palpitations') }}</td>
                        <td>{{ $cardio->palpitations_doctor }}</td>
                    @endif
                    @if($cardio->dizziness_doctor || $cardio->getValue('dizziness'))
                        <th>Dizziness</th>
                        <td>{{ $cardio->getValue('dizziness') }}</td>
                        <td>{{ $cardio->dizziness_doctor }}</td>
                    @endif
                </tr>
                <tr>
                    @if($cardio->doctor_details)
                        <th>On examination</th>
                        <td style="border-right:1px solid #ddd">{{ $cardio->doctor_details }}</td>
                    @endif
                </tr>
                </tbody>
            </table>
        @endif
        @if($respiratory->id != null)
            <table id="w-comman" class="ui table table_cus_v bs">
                <tbody>
                <tr>
                    <th colspan="6"><h5>Respiratory System</h5></th>
                </tr>

                <tr>
                    @if($respiratory->cough_doctor || $respiratory->getValue('cough'))
                        <th>Cough/ Sputum</th>
                        <td style="border-top: 1px solid #ddd">{{ $respiratory->getValue('cough') }}</td>
                        <td style="border-top: 1px solid #ddd">{{ $respiratory->cough_doctor }}</td>
                    @endif

                    @if($respiratory->fever_doctor || $respiratory->getValue('fever'))
                        <th style="border-top: 1px solid #ddd">Fever/ Sweat</th>
                        <td style="border-top: 1px solid #ddd">{{ $respiratory->getValue('fever') }}</td>
                        <td style="border-top: 1px solid #ddd">{{ $respiratory->fever_doctor }}</td>
                    @endif
                </tr>

                <tr>
                    @if($respiratory->sinusitis_doctor || $respiratory->getValue('sinusitis'))
                        <th>Sinusitis</th>
                        <td>{{ $respiratory->getValue('sinusitis') }}</td>
                        <td style="border-right:1px solid #ddd">{{ $respiratory->sinusitis_doctor}}</td>
                    @endif
                    @if($respiratory->chest_pain_doctor || $respiratory->getValue('chest_pain'))
                        <th>Chest Pain</th>
                        <td>{{ $respiratory->getValue('chest_pain') }}</td>
                        <td style="border-right:1px solid #ddd"> {{ $respiratory->chest_pain_doctor }}</td>
                    @endif
                </tr>
                <tr>
                    @if($respiratory->wheeze_doctor || $respiratory->getValue('wheeze'))
                        <th>Wheeze</th>
                        <td>{{ $respiratory->getValue('wheeze') }}</td>
                        <td style="border-right:1px solid #ddd">{{ $respiratory->wheeze_doctor }}</td>
                    @endif
                    @if($respiratory->hoarsness_doctor || $respiratory->getValue('hoarsness'))
                        <th>Hoarsness</th>
                        <td>{{ $respiratory->getValue('hoarsness') }}</td>
                        <td style="border-right:1px solid #ddd">{{ $respiratory->hoarsness_doctor }}</td>
                    @endif
                </tr>
                <tr>
                    @if($respiratory->doctor_details)
                        <th>On examination</th>
                        <td style="border-right:1px solid #ddd">{{ $respiratory->doctor_details }}</td>
                    @endif
                </tr>
                </tbody>
            </table>
        @endif

    </div>
@endif
@if($genitorinary->id != null)
    <div class="table_head_lft">
        <table id="w-comman" class="ui table table_cus_v bs">
            <tbody>
            <tr>
                <th colspan="6"><h5>Genitourinary examinations</h5></th>
            </tr>

            <tr>
                @if($genitorinary->fever_doctor || $genitorinary->getValue('fever'))
                    <th>Fever</th>
                    <td style="border-top: 1px solid #ddd">{{ $genitorinary->getValue('fever') }}</td>
                    <td style="border-top: 1px solid #ddd">{{ $genitorinary->fever_doctor }}</td>
                @endif
                @if($genitorinary->loin_pain_doctor || $genitorinary->getValue('loin_pain'))
                    <th style="border-top: 1px solid #ddd">Loin Pain</th>
                    <td style="border-top: 1px solid #ddd">{{ $genitorinary->getValue('loin_pain') }}</td>
                    <td style="border-top: 1px solid #ddd">{{ $genitorinary->loin_pain_doctor }}</td>
                @endif

            </tr>

            <tr>
                @if($genitorinary->dysuria_doctor || $genitorinary->getValue('dysuria'))
                    <th>Dysuria</th>
                    <td>{{ $genitorinary->getValue('dysuria') }}</td>
                    <td>{{ $genitorinary->dysuria_doctor }}</td>
                @endif
                @if($genitorinary->urethral_discharge_doctor || $genitorinary->getValue('urethral_discharge'))
                    <th>Urethral/ Vaginal discharge</th>
                    <td>{{ $genitorinary->getValue('urethral_discharge') }}</td>
                    <td>{{ $genitorinary->urethral_discharge_doctor }}</td>
                @endif
            </tr>

            <tr>
                @if($genitorinary->painful_sexual_intercourse_doctor || $genitorinary->getValue('painful_sexual_intercourse'))
                    <th>Painful sexual intercourse</th>
                    <td>{{ $genitorinary->getValue('painful_sexual_intercourse') }}</td>

                    <td>{{ $genitorinary->painful_sexual_intercourse_doctor }}</td>
                @endif
                @if($genitorinary->menarche_doctor || $genitorinary->getValue('menarche'))
                    <th>Menarche</th>
                    <td>{{ $genitorinary->getValue('menarche') }}</td>
                    <td>{{ $genitorinary->menarche_doctor }}</td>
                @endif
            </tr>
            <tr>
                @if($genitorinary->menopause_doctor || $genitorinary->getValue('menopause'))
                    <th>Menopause</th>
                    <td>{{ $genitorinary->getValue('menopause') }}</td>
                    <td>{{ $genitorinary->menopause_doctor }}</td>
                @endif
                @if($genitorinary->length_of_periods_doctor || $genitorinary->getValue('length_of_periods'))
                    <th>Length of periods</th>
                    <td>{{ $genitorinary->getValue('length_of_periods') }}</td>
                    <td>{{ $genitorinary->length_of_periods_doctor }}</td>
                @endif
            </tr>
            <tr>
                @if($genitorinary->amount_pain_doctor || $genitorinary->getValue('amount_pain'))
                    <th>Amount/ Pain</th>
                    <td>{{ $genitorinary->getValue('amount_pain') }}</td>
                    <td>{{ $genitorinary->amount_pain_doctor }}</td>
                @endif
                @if($genitorinary->LMP_doctor || ($genitorinary->LMP != "0000-00-00" && $genitorinary->LMP != null) )
                    <th>LMP</th>
                    <td>{{ $genitorinary->LMP != '0000-00-00' && !empty($genitorinary->LMP) ? date("d-m-Y", strtotime($genitorinary->LMP)) : '' }}</td>
                    <td>{{ $genitorinary->LMP_doctor}}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
@endif
@if($gastro->id != null)
    <div class="table_head_lft">
        <table  id="w-comman" class="ui table table_cus_v bs">
            <tbody>
            <tr>
                <th colspan="6"><h5>Gastrointestinal examination</h5></th>
            </tr>

            <tr>
                @if($gastro->abdominal_pain_doctor || $gastro->getValue('abdominal_pain'))
                    <th>Abdominal pain</th>
                    <td style="border-top: 1px solid #ddd">{{ $gastro->getValue('abdominal_pain') }}</td>
                    <td style="border-top: 1px solid #ddd">{{ $gastro->abdominal_pain_doctor}}</td>
                @endif

                @if($gastro->nausea_doctor || $gastro->getValue('nausea'))
                    <th class="border_left" style="border-top: 1px solid #ddd">Nausea/ vomiting/haematemesis</th>
                    <td style="border-top: 1px solid #ddd">{{ $gastro->getValue('nausea') }}</td>
                    <td style="border-top: 1px solid #ddd">{{ $gastro->nausea_doctor }}</td>
                @endif
            </tr>

            <tr>
                @if($gastro->dysphagia_doctor || $gastro->getValue('dysphagia'))
                    <th>Dysphagia</th>
                    <td>{{ $gastro->getValue('dysphagia') }}</td>
                    <td>{{ $gastro->dysphagia_doctor }}</td>
                @endif
                @if($gastro->indigestion_doctor || $gastro->getValue('indigestion'))
                    <th class="border_left">Indigestion</th>
                    <td>{{ $gastro->getValue('indigestion') }}</td>
                    <td>{{ $gastro->indigestion_doctor}}</td>
                @endif
            </tr>
            <tr>
                @if($gastro->change_in_bowel_habits_doctor || $gastro->getValue('change_in_bowel_habits'))
                    <th>Change in Bowel habits</th>
                    <td>{{ $gastro->getValue('change_in_bowel_habits') }}</td>
                    <td> {{ $gastro->change_in_bowel_habits_doctor }}</td>
                @endif
                @if($gastro->diarrhoea_constipation_doctor || $gastro->getValue('diarrhoea_constipation'))
                    <th class="border_left">Diarrhoea/ constipation</th>
                    <td>{{ $gastro->getValue('diarrhoea_constipation') }}</td>
                    <td>{{ $gastro->diarrhoea_constipation_doctor }}</td>
                @endif
            </tr>
            <tr>
                @if($gastro->rectal_bleeding_doctor || $gastro->getValue('rectal_bleeding'))
                    <th>Rectal Bleeding</th>
                    <td>{{ $gastro->getValue('rectal_bleeding') }}</td>
                    <td>{{ $gastro->rectal_bleeding_doctor }}</td>
                @endif
                @if($gastro->weight_change_doctor || $gastro->getValue('weight_change'))
                    <th class="border_left">Appetite/ weight change</th>
                    <td>{{ $gastro->getValue('weight_change') }}</td>
                    <td>{{ $gastro->weight_change_doctor }}</td>
                @endif
            </tr>
            <tr>
                @if($gastro->dark_urine_doctor || $gastro->getValue('dark_urine'))
                    <th>Dark Urine or pale stools</th>
                    <td>{{ $gastro->getValue('dark_urine') }}</td>
                    <td style="border-right:1px solid #ddd">{{ $gastro->dark_urine_doctor }}</td>
                @endif
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
@endif

