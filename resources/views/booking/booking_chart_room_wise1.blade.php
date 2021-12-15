@extends('layouts.front.web_layout')
@section('content')
    <div class="admin_wrapper signup">
        <header>
            <div class="logo_wrapper wow fadeInDown">
                <h1>Kayakalp</h1>
            </div>
        </header>
        <div class="wrapper">
            <div class="chart_container">
                <table  class="table_outer" align="center" cellpadding="0" cellspacing="0" width="100%" height="100%">
                    <tr>
                        <th class="heading">ACCOMMODATION STATUS CHART - ROOM WISE</th>
                    </tr>
                    <tr>
                        <td>AS OF <span>"%date%"</span></td>
                    </tr>
                    <tr>
                        <td class="table">
                            <table cellpadding="0" cellspacing="0" >
                                <tr>
                                    <td><a href="#">« Previous Day</a></td>
                                    <td>See for date <input type="date" placeholder="Year > Month > Date"></td>
                                    <td><a href="#">Next Day »</a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th class="over_status">OVER ALL STATUS:</th>
                    </tr>
                    <tr>
                        <td class="overall_inner table">
                            <table cellpadding="0" cellspacing="0" >
                                <tr>
                                    <td align="right">Cottage Deluxe</td>
                                    <td align="left">1M 1W / 2</td>
                                    <td align="right">Cottage Deluxe</td>
                                    <td align="left">1M 1W / 2</td>
                                    <td align="right">Cottage Deluxe</td>
                                    <td align="left">1M 1W / 2</td>
                                </tr>
                                <tr class="bottom_border">
                                    <td align="right">Cottage Deluxe</td>
                                    <td align="left">1M 1W / 2</td>
                                    <td align="right">Cottage Deluxe</td>
                                    <td align="left">1M 1W / 2</td>
                                    <td align="right">Cottage Deluxe</td>
                                    <td align="left">1M 1W / 2</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th class="over_status">ROOM WISE STATUS</th>
                    </tr>
                    <tr>
                        <td class="table">
                            <table cellpadding="0" cellspacing="0" >
                                <tr>
                                    <th align="right" class="text-right">Building Name</th>
                                    <th>KETAN</th>
                                    <th>NIKET</th>
                                    <th>NILAY</th>
                                    <th>BASERA</th>
                                </tr>
                                <tr class="bottom_border">
                                    <td align="right">Type</td>
                                    <td><strong>Cottage & Delux</strong></td>
                                    <td><strong>Deluxe Double Bed</strong></td>
                                    <td><strong>Doubel Bed Room</strong></td>
                                    <td><strong>Dormitory</strong></td>
                                </tr>
                                <tr class="bottom_border">
                                    <td class="table">
                                        <table cellpadding="0" cellspacing="0"  class="room_type">
                                            <tr>
                                                <th align="right" class="text-right">Room No.</th>
                                            </tr>
                                            <tr>
                                                <td  align="right" class="text-right">Type</td>
                                            </tr>
                                            <tr>
                                                <td  align="right">Bed-1</td>
                                            </tr>
                                            <tr>
                                                <td  align="right">Bed-2</td>
                                            </tr>
                                            <tr>
                                                <td  align="right">Bed-3</td>
                                            </tr>
                                            <tr>
                                                <td  align="right">Bed-4</td>
                                            </tr>
                                            <tr>
                                                <td  align="right">Bed-5</td>
                                            </tr>
                                            <tr>
                                                <td  align="right">Bed-6</td>
                                            </tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr>
                                                <td  align="right" class="text-right">Extra Bed-1</td>
                                            </tr>
                                            <tr>
                                                <td  align="right" class="text-right">Extra Bed-2</td>
                                            </tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr>
                                                <th align="right" class="text-right">Extra Services:</th>
                                            </tr>
                                            <tr>
                                                <td  align="right" class="text-right">Extra Service-1 (i.e. Heater)</td>
                                            </tr>
                                            <tr>
                                                <td  align="right" class="text-right">Extra Service-2 (I.e. Blower)</td>
                                            </tr>
                                            <tr>
                                                <td  align="right" class="text-right">Extra Service-3 (i.e. Heat Pillar)</td>
                                            </tr>
                                            <tr>
                                                <td  align="right" class="text-right">Extra Service-4 (i.e. Reverse Cycle A/C)</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <th class="orange">101</th>
                                                <th class="blue">102</th>
                                                <th class="orange">103</th>
                                                <th class="orange">104</th>
                                                <th class="orange">105</th>
                                                <th class="orange">106</th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td>CD</td>
                                                <td>CD</td>
                                                <td>C</td>
                                                <td>C</td>
                                                <td>C</td>
                                                <td>C</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2" class="brown">SO</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td class="green left_white">V</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <th>&nbsp;</th>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>

                                        </table>
                                    </td>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <th class="green">101</th>
                                                <th class="orange">102</th>
                                                <th class="orange">103</th>
                                                <th class="blue">104</th>
                                                <th class="orange">105</th>
                                                <th class="orange">106</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                            <tr>
                                                <td>DDB</td>
                                                <td>DDB</td>
                                                <td>DDB</td>
                                                <td>DDB</td>
                                                <td>DDB</td>
                                                <td>DDB</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td class="green">V</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td class="green">V</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="green">V</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>

                                        </table>
                                    </td>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <th>101</th>
                                                <th>102</th>
                                                <th>103</th>
                                                <th>104</th>
                                                <th>105</th>
                                                <th>106</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                            <tr>
                                                <td>DB</td>
                                                <td>DB</td>
                                                <td>DB</td>
                                                <td>DB</td>
                                                <td>DB</td>
                                                <td>DB</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>

                                        </table>
                                    </td>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <th>MAN</th>
                                                <th>WOMAN</th>
                                            </tr>
                                            <tr>
                                                <td>DR</td>
                                                <td>DR</td>
                                            </tr>
                                            <tr>
                                                <td class="orange">B</td>
                                                <td class="green">V</td>
                                            </tr>
                                            <tr>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                            </tr>
                                            <tr>
                                                <td class="green">V</td>
                                                <td class="orange">B</td>
                                            </tr>
                                            <tr>
                                                <td class="green">V</td>
                                                <td class="orange">B</td>
                                            </tr>
                                            <tr>
                                                <td class="orange">B</td>
                                                <td class="green">V</td>
                                            </tr>
                                            <tr>
                                                <td class="orange">B</td>
                                                <td class="green">V</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <td><strong>Colour Coding</strong></td><tr>
                                            <tr  class="orange"><td><strong>FB (Fully Booked)</strong></td><tr>
                                            <tr  class="blue"><td><strong>PB (Partially Booked)</strong></td><tr>
                                            <tr  class="dark_yellow"><td><strong>EBA (Extra Bed Available)</strong></td><tr>
                                            <tr  class="green"><td><strong>FV (Fully Vacant)</strong></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <td><strong>Character Coding</strong></td></tr>
                                            <tr class="orange"><td><strong>B = Booked</strong></td></tr>
                                            <tr class="green"><td><strong>V = Vacant</strong></td></tr>
                                            <tr class="brown"><td><strong>SO = Single Occupancy</strong></td></tr>
                                            <tr><td>&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </div>
        </div><!--wrapper ends here-->
    </div>
@endsection
