@if($lab_tests->count() > 0)
    <div class="table_head_lft">
        <table class="ui table table_cus_v last_row_bdr">
          <thead>
              <tr>
                <th colspan="5">
                  <h5>Lab Tests</h5>
                </th>
              </tr>
              <tr>
                  <th>Date</th>
                  <th>Tests</th>
                  <th>Result</th>
                  <th>Price</th>
                  @if(!isset($print))
                    <th>Report</th>
                  @endif
              </tr>
          </thead>
          <tbody>
          @foreach($lab_tests as $lab_test)
              <tr>
                  <td align="center">{{ $lab_test->date_date }}</td>
                  <td align="center">{{ $lab_test->getTestsName() }}</td>
                  <td align="center">{{ $lab_test->note }}</td>
                  <td align="center">{{ $lab_test->getAllPrice() }}</td>
                  @if(!isset($print))
                    <td align="center">
                      @if($lab_test->test_status == 1)
                          <a title="Download Report" id="download_report_{{ $lab_test->id }}" href="{{ url("admin/patient/download_report/".$lab_test->id) }}">
                              Download
                          </a>
                      @endif
                    </td>
                  @endif
              </tr>
          @endforeach
          </tbody>
        </table> 
    </div>
@endif
