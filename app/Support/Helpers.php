<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


function lang($path = null, $string = null)
{
    $lang = $path;
    if (trim($path) != '' && trim($string) == '') {
        $lang = \Lang::get($path);
    } elseif (trim($path) != '' && trim($string) != '') {
        $lang = \Lang::get($path, ['attribute' => $string]);
    }
    return $lang;
}

function isSuperAdmin()
{
    if(\Auth::check()) {
        return (\Auth::user()->user_type == 1) ? true : false;
    }
}

function isEmp()
{
    if(\Auth::check()) {
        return (\Auth::user()->user_type == 4) ? true : false;
    }
}
function isAgent()
{
    if(\Auth::check()) {
        return (\Auth::user()->user_type == 3) ? true : false;
    }
}
function isManager()
{
    if(\Auth::check()) {
        return (\Auth::user()->user_type == 5) ? true : false;
    }
}

function pageIndex($index, $page, $perPage)
{
    return (($page - 1) * $perPage) + $index;
}

function paginationControls($page, $total, $perPage = 20)
{
    $paginates = '';
    $curPage = $page;
    $page -= 1;
    $previousButton = true;
    $next_btn = true;
    $first_btn = false;
    $last_btn = false;
    $noOfPaginations = ceil($total / $perPage);

    /* ---------------Calculating the starting and ending values for the loop----------------------------------- */
    if ($curPage >= 10) {
        $start_loop = $curPage - 5;
        if ($noOfPaginations > $curPage + 5) {
            $end_loop = $curPage + 5;
        } elseif ($curPage <= $noOfPaginations && $curPage > $noOfPaginations - 9) {
            $start_loop = $noOfPaginations - 9;
            $end_loop = $noOfPaginations;
        } else {
            $end_loop = $noOfPaginations;
        }
    } else {
        $start_loop = 1;
        if ($noOfPaginations > 10)
            $end_loop = 10;
        else
            $end_loop = $noOfPaginations;
    }

    $paginates .= '<div class="col-sm-5 padding0 pull-left custom-martop">' .
        lang('Jump to ') .
        '<input type="text" class="goto" size="1" />
                    <button type="button" id="go_btn" class="small-btn small-btn-primary"> <span class="fa fa-arrow-right"> </span> </button> ' .
        lang('Pages') . ' ' .  $curPage . ' of <span class="_total">' . $noOfPaginations . '</span> | ' . lang('Total Records', $total) .
        '</div> <ul class="pagination pagination-sm pull-right custom-martop">';

    // FOR ENABLING THE FIRST BUTTON
    if ($first_btn && $curPage > 1) {
        $paginates .= '<li p="1" class="disabled">
                            <a href="javascript:void(0);">' .
            lang('common.first')
            . '</a>
                       </li>';
    } elseif ($first_btn) {
        $paginates .= '<li p="1" class="disabled">
                            <a href="javascript:void(0);">' .
            lang('common.first')
            . '</a>
                       </li>';
    }

    // FOR ENABLING THE PREVIOUS BUTTON
    if ($previousButton && $curPage > 1) {
        $pre = $curPage - 1;
        $paginates .= '<li p="' . $pre . '" class="_paginate">
                            <a href="javascript:void(0);" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                       </li>';
    } elseif ($previousButton) {
        $paginates .= '<li class="disabled">
                            <a href="javascript:void(0);" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                       </li>';
    }

    for ($i = $start_loop; $i <= $end_loop; $i++) {
        if ($curPage == $i)
            $paginates .= '<li p="' . $i . '" class="active"><a href="javascript:void(0);">' . $i . '</a></li>';
        else
            $paginates .= '<li p="' . $i . '" class="_paginate"><a href="javascript:void(0);">' . $i . '</a></li>';
    }

    // TO ENABLE THE NEXT BUTTON
    if ($next_btn && $curPage < $noOfPaginations) {
        $nex = $curPage + 1;
        $paginates .= '<li p="' . $nex . '" class="_paginate">
                            <a href="javascript:void(0);" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                       </li>';
    } elseif ($next_btn) {
        $paginates .= '<li class="disabled">
                            <a href="javascript:void(0);" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                       </li>';
    }

    // TO ENABLE THE END BUTTON
    if ($last_btn && $curPage < $noOfPaginations) {
        $paginates .= '<li p="' . $noOfPaginations . '" class="_paginate">
                            <a href="javascript:void(0);">' .
            lang('common.last')
            . '</a>
                       </li>';
    } elseif ($last_btn) {
        $paginates .= '<li p="' . $noOfPaginations . '" class="disabled">
                            <a href="javascript:void(0);">' .
            lang('common.last')
            . '</a>
                       </li>';
    }

    $paginates .= '</ul>';

    return $paginates;
}

function authUserIdNull() {
    $id = null;
    if (\Auth::check()) {
        $id = \Auth::user()->id;
    }
    return $id;
}

function get_attendance($month, $year, $user_id){

    $em_info = App\Models\EmployeeInfo::where('user_id', $user_id)->where('employee_code', '!=', NULL)->select('shift_id')->first(); 

    $daysInMonths = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    
    $date = $year.'-'.$month;
    $date = date('Y-m', strtotime($date));

    $sal_yes_no = App\Models\SalaryGenerate::where('month_year', $date)->where('user_id', $user_id)->where('status', 1)->count();


    if($sal_yes_no == 0){
        $data['sal_gen'] = 0;
    } else {
        $data['sal_gen'] = 1;
    }

    // $year = date('Y');
    // $month = $month; 
     

    $data['present'] = App\Models\EmpAttendance::where('user_id', $user_id)->whereRaw('date_format(created_at,"%Y-%m")'."='".$date. "'")->select('total_time', 'in_time', 'out_time')->count();
    $old_date = $date;
    $leave = App\Models\ShiftOfDay::where('shift_id', $em_info->shift_id)->where('month', $old_date)->count();

    // dd($leave);

    $working_day = $daysInMonths - $leave;
    $data['abs'] = $working_day - $data['present'];
    $user = App\Models\User::where('id', $user_id)->select('salary')->first();
  
    $salary = $user->salary;
    $oneday_sal = $salary/$working_day;

    $present = $data['present'];
    $leave_day = 0;

    if($working_day > $present){
        $leave_start = App\Models\Leave::where('user_id', $user_id)->where('status', 'Approved')->whereRaw('date_format(start_date,"%Y-%m")'."='".$date . "'")->pluck('id')->toArray();
        $leave_end = App\Models\Leave::where('user_id', $user_id)->whereNotIn('id', $leave_start)->where('status', 'Approved')->whereRaw('date_format(end_date,"%Y-%m")'."='".$date . "'")->pluck('id')->toArray();
        $leave_all = array_merge($leave_start, $leave_end);
        if(count($leave_all) != 0){
        foreach ($leave_all as $value) {
            $leave = App\Models\Leave::where('id', $value)->select('start_date', 'end_date')->first();
            $start_date = substr($leave->start_date, 0, -3);
            $end_date = substr($leave->end_date, 0, -3);
            if($start_date == $date && $end_date == $date){
                $start_date = mb_substr($leave->start_date, -2); 
                $end_date = mb_substr($leave->end_date, -2); 
                $days = $end_date - $start_date;
                $leave_day += (int)$days+1;
            } else if($start_date == $date){
                $numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                $result = mb_substr($leave->start_date, -2);
                $leave_day += 1 + $numDays - $result;
            } else {
                $leave_day += (int)mb_substr($leave->end_date, -2);
            }
        }
        }
    }

    $data['leave_day'] = $leave_day;
    $present = $present + $leave_day;
    $data['salary'] = $present*$oneday_sal;
    $absent = $data['abs'] - $leave_day;
    $data['abs_deduction'] = $absent*$oneday_sal;
    $data['working_day'] = $working_day;
    $data['total_days'] = $daysInMonths;
    
    return $data;

}


function get_user_info($user_id){
    $user = App\Models\User::where('id', $user_id)->select('name')->first();
    $data['name'] = $user->name;
    $user = App\Models\EmployeeInfo::where('user_id', $user_id)->where('employee_code', '!=', NULL)->select('employee_code')->first();
    $data['employee_code'] = $user->employee_code;
    return $data;
}







