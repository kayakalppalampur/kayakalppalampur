<!-- @if($ashtvidh->id != null || $aturpariksha->id != null || $doshpariksha->id != null || $dhatupariksha->id != null)
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>
            <tr>
                <h3 style="border-top: 1px solid #ddd; border-right: 1px solid #ddd; border-left: 1px solid #ddd">
                    <center>Ayurvedic Examinations</center>
                </h3>
            </tr>
            </tbody>
        </table>
    </div>
@endif -->

@if($ashtvidh->id != null)
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>
            <tr>
                <th colspan="10"><h5>{{ trans('laralum.ashtvidh_pariksha') }}</h5></th>
            </tr>
            <tr>
                @if($ashtvidh->pulse)
                    <th>{{ trans('laralum.pulse') }}</th>
                    <td style="border-top: 1px solid #ddd;word-break: break-all;">{{ $ashtvidh->pulse }} {{ trans('laralum.speed_per_mins') }}</td>
                    <td></td>
                @endif
                @if($ashtvidh->pulse_issue || $ashtvidh->pulse_comment)
                    <th style="border-top: 1px solid #ddd;word-break: break-all;">{{ trans('laralum.pulse_issue') }}</th>
                    <td style="border-top: 1px solid #ddd;word-break: break-all;">{{ $ashtvidh->pulse_issue}}</td>
                    <td style="border-top: 1px solid #ddd;word-break: break-all;">{{ $ashtvidh->pulse_comment}}</td>
                @endif
                @if($ashtvidh->getValue('faecal_matter'))
                    <th style="border-top: 1px solid #ddd;word-break: break-all;">{{ trans('laralum.faecal_matter') }}</th>
                    <td style="border-top: 1px solid #ddd;word-break: break-all;">{{ $ashtvidh->getValue('faecal_matter') }}</td>
                    <td></td>
                @endif
            </tr>
            <tr>
                @if($ashtvidh->faecal_matter_speed_days || $ashtvidh->faecal_matter_comment)
                    <th style="border-top: 1px solid #ddd;word-break: break-all;">{{ trans('laralum.speed_per_days') }}</th>
                    <td style="border-top: 1px solid #ddd;word-break: break-all;">{{ $ashtvidh->faecal_matter_speed_days}}</td>
                    <td style="border-top: 1px solid #ddd;word-break: break-all;">{{ $ashtvidh->faecal_matter_comment}}</td>
                @endif
                @if($ashtvidh->faecal_matter_liquid_speed_days)
                    <th>{{ trans('laralum.faecal_matter_liquid') }} {{ trans('laralum.speed_per_days') }}</th>
                    <td>{{ $ashtvidh->faecal_matter_liquid_speed_days }} </td>
                    <td></td>
                @endif
                @if($ashtvidh->faecal_matter_liquid)
                    <th style="border-top: 1px solid #ddd;word-break: break-all;">{{ trans('laralum.varna') }}</th>
                    <td style="word-break: break-all;">{{ $ashtvidh->faecal_matter_liquid }}</td>
                    <td></td>
                @endif
                

            </tr>
            <tr>
            @if($ashtvidh->faecal_matter_liquid_speed_nights || $ashtvidh->faecal_matter_liquid_comment)
                    <th style="border-top: 1px solid #ddd;word-break: break-all;">{{ trans('laralum.speed_per_nights') }}</th>
                    <td style="word-break: break-all;">{{ $ashtvidh->faecal_matter_liquid_speed_nights }}</td>
                    <td style="word-break: break-all;">{{ $ashtvidh->faecal_matter_liquid_comment }}</td>
                @endif
                @if($ashtvidh->getValue('tongue') || $ashtvidh->tongue_comment)
                    <th> {{ trans('laralum.tongue') }}</th>
                    <td style="word-break: break-all;">{{ $ashtvidh->getValue('tongue') }} @if($ashtvidh->getValue('tongue') != '' && $ashtvidh->getValue('tongue_2') != '') , @endif {{ $ashtvidh->getValue('tongue_2') }}</td>
                    <td>{{ $ashtvidh->tongue_comment }}</td>
                @endif
                @if($ashtvidh->getValue('speech') || $ashtvidh->speech_comment)
                    <th>{{ trans('laralum.speech') }}</th>
                    <td style="word-break: break-all;">{{ $ashtvidh->getValue('speech') }}</td>
                    <td style="word-break: break-all;">{{ $ashtvidh->speech_comment }}</td>
                @endif
                

            </tr>
            <tr>
            @if($ashtvidh->getValue('skin') || $ashtvidh->skin_comment)
                    <th>{{ trans('laralum.skin') }}</th>
                    <td style="border-top: 1px solid #ddd;word-break: break-all;">{{ $ashtvidh->getValue('skin')}}</td>
                    <td style="border-top: 1px solid #ddd;word-break: break-all;">{{ $ashtvidh->skin_comment }}</td>
                @endif
                @if($ashtvidh->getValue('eyes') || $ashtvidh->eyes_comment)
                    <th>{{ trans('laralum.eyes') }}</th>
                    <td>{{ $ashtvidh->getValue('eyes') }}</td>
                    <td style="border-top: 1px solid #ddd;word-break: break-all;">{{ $ashtvidh->eyes_comment }}</td>
                @endif
                @if($ashtvidh->getValue('body_build'))
                    <th>{{ trans('laralum.body_build') }}</th>
                    <td style="border-top: 1px solid #ddd;word-break: break-all;">{{ $ashtvidh->getValue('body_build') }}</td>
                    <td></td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
@endif
@if($aturpariksha->id != null)
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>
            <tr>
                <th colspan="10"><h5>{{  trans('laralum.atur_pariksha') }}</h5></th>
            </tr>
            <tr>
                @if($aturpariksha->getValue('prakriti'))
                    <th>{{  trans('laralum.prakriti') }}</th>
                    <td style="border-top: 1px solid #ddd;word-break: break-all;">{{ $aturpariksha->getValue('prakriti') }}</td>
                @endif
                @if($aturpariksha->getValue('saar'))
                    <th style="border-top: 1px solid #ddd;word-break: break-all;">{{  trans('laralum.saar') }}</th>
                    <td style="border-top: 1px solid #ddd; border-right:1px solid #ddd;word-break: break-all;">{{ $aturpariksha->getValue('saar') }}</td>
                @endif

            </tr>
            <tr>
                @if($aturpariksha->getValue('sanhanan'))
                    <th>{{  trans('laralum.sanhanan') }}</th>
                    <td>{{ $aturpariksha->getValue('sanhanan') }}</td>
                @endif
                @if($aturpariksha->praman)
                    <th>{{  trans('laralum.praman') }}</th>
                    <td style="border-right:1px solid #ddd;word-break: break-all;">{{ $aturpariksha->praman }} {{  trans('laralum.lambai') }}</td>
                @endif

            </tr>
            <tr>
                @if($aturpariksha->getValue('satmyaya'))
                    <th>{{  trans('laralum.satmyaya') }}</th>
                    <td>{{ $aturpariksha->getValue('satmyaya') }}</td>
                @endif
                @if($aturpariksha->getValue('satva'))
                    <th>{{  trans('laralum.satva') }}</th>
                    <td style="border-right:1px solid #ddd;word-break: break-all;">{{ $aturpariksha->getValue('satva') }}</td>
                @endif

            </tr>
            <tr>
                @if($aturpariksha->getValue('ahaar_shakti'))
                    <th>{{  trans('laralum.ahaar_shakti') }}</th>
                    <td style="word-break: break-all;">{{ $aturpariksha->getValue('ahaar_shakti') }}</td>
                @endif
                @if($aturpariksha->getValue('vyayaam_shakti'))
                    <th>{{  trans('laralum.vyayaam_shakti') }}</th>
                    <td style="border-right:1px solid #ddd;word-break: break-all;">{{ $aturpariksha->getValue('vyayaam_shakti') }}</td>
                @endif

            </tr>
            <tr>
                @if($aturpariksha->getValue('vaya'))
                    <th>{{  trans('laralum.vaya') }}</th>
                    <td style="word-break: break-all;">{{ $aturpariksha->getValue('vaya') }}</td>
                @endif
                @if($aturpariksha->varsh)
                    <th>{{  trans('laralum.varsh') }}</th>
                    <td style="border-right:1px solid #ddd;word-break: break-all;">{{ $aturpariksha->varsh }}</td>
                @endif
            </tr>

            <tr>
                @if($aturpariksha->getValue('bal'))
                    <th>{{  trans('laralum.bal') }}</th>
                    <td style="border-right:1px solid #ddd;word-break: break-all;">{{ $aturpariksha->getValue('bal') }}</td>
                @endif
                @if($aturpariksha->drishya)
                    <th>{{  trans('laralum.drishya') }}</th>
                    <td style="border-right:1px solid #ddd;word-break: break-all;">{{ $aturpariksha->drishya }}</td>
                @endif

            </tr>
            </tbody>
        </table>
    </div>

    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>
            <tr>
                <th colspan="10"><h5>{{  trans('laralum.desh') }}</h5></th>
            </tr>
            <tr>
                @if($aturpariksha->getValue('uttpatti_desh'))
                    <th>{{  trans('laralum.utpatti_desh') }}</th>
                    <td style="border-right:1px solid #ddd;word-break: break-all;">{{ $aturpariksha->getValue('uttpatti_desh') }}</td>
                @endif
                @if($aturpariksha->getValue('vyadhit_desh'))
                    <th style="border-right:1px solid #ddd;word-break: break-all;">{{  trans('laralum.vyadhit_desh') }}</th>
                    <td style="border-right:1px solid #ddd;word-break: break-all;">{{ $aturpariksha->getValue('vyadhit_desh') }}</td>
                @endif
            </tr>
            <tr>
                @if($aturpariksha->getValue('chikitsa_desh'))
                    <th>{{  trans('laralum.chikitsa_desh') }}</th>
                    <td style="word-break: break-all;">{{ $aturpariksha->getValue('chikitsa_desh') }}</td>
                @endif
                @if($aturpariksha->getValue('kaal'))
                    <th>{{  trans('laralum.kaal_ritu') }}</th>
                    <td style="word-break: break-all;">{{ $aturpariksha->getValue('kaal') }}</td>
                @endif

            </tr>
            <tr>
                @if($aturpariksha->getValue('anal'))
                    <th>{{  trans('laralum.anal') }}</th>
                    <td style="word-break: break-all;">{{ $aturpariksha->getValue('anal') }}</td>
                @endif

            </tr>
            </tbody>
        </table>
    </div>
@endif

@if($doshpariksha->id != null)
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>
            <!-- <tr>
                <h3 style="border-top: 1px solid #ddd; border-right: 1px solid #ddd; border-left: 1px solid #ddd">
                    <center>{{  trans('laralum.dosh_priksha') }}</center>
                </h3>
            </tr> -->
            <tr>
                <th colspan="10" style="text-align: center;"><h3>{{  trans('laralum.dosh_priksha') }}</h3></th>
            </tr>
            <tr>
                <th colspan="10"><h5>{{  trans('laralum.vat_dosh') }}</h5></th>
            </tr>
            <tr>
                @if($doshpariksha->getValue('vat_dosh_growth'))
                    <th>{{  trans('laralum.vridhi') }}</th>
                    <td style="border-right:1px solid #ddd;word-break: break-all;">{{ $doshpariksha->getValue('vat_dosh_growth') }}</td>
                @endif
                @if($doshpariksha->getValue('vat_dosh_decay'))
                    <th style="border-right:1px solid #ddd;word-break: break-all;">{{  trans('laralum.kshaya') }}</th>
                    <td style="border-right:1px solid #ddd;word-break: break-all;">{{ $doshpariksha->getValue('vat_dosh_decay') }}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>

            <tr>
                <th colspan="10"><h5>{{  trans('laralum.pitt_dosh') }}</h5></th>
            </tr>
            <tr>
                @if($doshpariksha->getValue('pitt_dosh_growth'))
                    <th>{{  trans('laralum.vridhi') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $doshpariksha->getValue('pitt_dosh_growth') }}</td>
                @endif
                @if($doshpariksha->getValue('pitt_dosh_decay'))
                    <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $doshpariksha->getValue('pitt_dosh_decay') }}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>

            <tr>
                <th colspan="10"><h5>{{  trans('laralum.kaph_dosh') }}</h5></th>
            </tr>
            <tr>
                @if($doshpariksha->getValue('kaph_dosh_growth'))
                    <th>{{ trans('laralum.vridhi') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $doshpariksha->getValue('kaph_dosh_growth') }}</td>
                @endif
                @if($doshpariksha->getValue('kaph_dosh_decay'))
                    <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $doshpariksha->getValue('kaph_dosh_decay') }}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
@endif
@if($dhatupariksha->id != null)
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>
            <!-- <tr>
                <h3 style="border-top: 1px solid #ddd; border-right: 1px solid #ddd; border-left: 1px solid #ddd">
                    <center>{{  trans('laralum.dhatu_priksha') }}</center>
                </h3>
            </tr> -->
            <tr>
                <th colspan="10" style="text-align: center;"><h3>{{  trans('laralum.dhatu_priksha') }}</h3></th>
            </tr>
            <tr>
                <th colspan="10"><h5>{{  trans('laralum.ras') }}</h5></th>
            </tr>
            <tr>
                @if($dhatupariksha->getValue('ras_growth'))
                    <th>{{  trans('laralum.vridhi') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('ras_growth') }}</td>
                @endif
                @if($dhatupariksha->getValue('ras_decay'))
                    <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('ras_decay') }}</td>
                @endif

            </tr>
            </tbody>
        </table>
    </div>
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>

            <tr>
                <th colspan="10"><h5>{{  trans('laralum.rakt') }}</h5></th>
            </tr>
            <tr>
                @if($dhatupariksha->getValue('rakht_growth'))
                    <th>{{  trans('laralum.vridhi') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('rakht_growth') }}</td>
                @endif
                @if($dhatupariksha->getValue('rakht_decay'))
                    <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('rakht_decay') }}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>

            <tr>
                <th colspan="10"><h5>{{  trans('laralum.maans') }}</h5></th>
            </tr>
            <tr>
                @if($dhatupariksha->getValue('maans_growth'))
                    <th>{{  trans('laralum.vridhi') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('maans_growth') }}</td>
                @endif
                @if($dhatupariksha->getValue('maans_decay'))
                    <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('maans_decay') }}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>

            <tr>
                <th colspan="10"><h5>{{  trans('laralum.med') }}</h5></th>
            </tr>
            <tr>
                @if($dhatupariksha->getValue('med_growth'))
                    <th>{{  trans('laralum.vridhi') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('med_growth') }}</td>
                @endif
                @if($dhatupariksha->getValue('med_decay'))
                    <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('med_decay') }}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>

            <tr>
                <th colspan="10"><h5>{{  trans('laralum.asthi') }}</h5></th>
            </tr>
            <tr>
                @if($dhatupariksha->getValue('asthi_growth'))
                    <th>{{  trans('laralum.vridhi') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('asthi_growth') }}</td>
                @endif
                @if($dhatupariksha->getValue('asthi_decay'))
                    <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('asthi_decay') }}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>

            <tr>
                <th colspan="10"><h5>{{  trans('laralum.majja') }}</h5></th>
            </tr>
            <tr>
                @if($dhatupariksha->getValue('majja_growth'))
                    <th>{{  trans('laralum.vridhi') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('majja_growth') }}</td>
                @endif
                @if($dhatupariksha->getValue('majja_decay'))
                    <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('majja_decay') }}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>

            <tr>
                <th colspan="10"><h5>{{  trans('laralum.shukra') }}</h5></th>
            </tr>
            <tr>
                @if($dhatupariksha->getValue('shukra_growth'))
                    <th>{{  trans('laralum.vridhi') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('shukra_growth') }}</td>
                @endif
                @if($dhatupariksha->getValue('shukra_decay'))
                    <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('shukra_decay') }}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>
            <tr>
                @if($dhatupariksha->rog_nidan)
                    <th>{{  trans('laralum.rog_nidan') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->rog_nidan }}</td>
                @endif
                @if($dhatupariksha->vyadhi_ka_naam)
                    <th style="border-top: 1px solid #ddd">{{  trans('laralum.vydhi_ka_naam') }}</th>
                    <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->vyadhi_ka_naam }}</td>
                @endif

            </tr>
            </tbody>
        </table>
    </div>
@endif