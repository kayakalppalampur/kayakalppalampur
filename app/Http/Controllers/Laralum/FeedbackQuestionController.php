<?php

namespace App\Http\Controllers\Laralum;

use App\FeedbackQuestion;
use App\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use SnappyPDF;

class FeedbackQuestionController extends Controller
{
    //
    /**
     * feedback_question listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.feedback_questions');

        if ($request->get("question_id") != null) {
            $question = FeedbackQuestion::find($request->get("question_id"));

            $question->update([
                'question' => $request->get("question_" . $question->id)
            ]);
        }

        $feedback_questions = FeedbackQuestion::select('*');

        if (!\Auth::user()->isAdmin()) {
            $feedback_questions = $feedback_questions->where('created_by', \Auth::user()->id);
        }
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        $count = $feedback_questions->count();
        if ($per_page == "All") {
            $pagination = false;
        }
        if ($pagination == true) {
            $feedback_questions = $feedback_questions->paginate($per_page);
        } else {
            $feedback_questions = $feedback_questions->get();
        }

        return view('laralum.feedback_question.index', compact('feedback_questions', 'count'));
    }

    /**
     * feedback_question details with replies
     * @return View
     */
    public function view($id)
    {
        Laralum::permissionToAccess('admin.admin_settings.feedback_questions');
        $feedback_question = FeedbackQuestion::find($id);

        return view('laralum.feedback_question.view', compact('feedback_question'));
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.feedback_questions');

        # Find the feedback_question
        $row = FeedbackQuestion::findOrFail($id);
        \Session::put('feedback_question_id', $id);

        # Get all the data
        $data_index = 'feedback_questions';
        require('Data/Create/Get.php');

        # Return the view
        return view('laralum/feedback_questions/edit', [
            'row' => $row,
            'fields' => $fields,
            'confirmed' => $confirmed,
            'empty' => $empty,
            'encrypted' => $encrypted,
            'hashed' => $hashed,
            'masked' => $masked,
            'table' => $table,
            'code' => $code,
            'wysiwyg' => $wysiwyg,
            'relations' => $relations,
        ]);
    }

    public function update($id, Request $request)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.feedback_questions');

        # Find the row
        $feedback_question = FeedbackQuestion::findOrFail($id);

        try {

            if ($feedback_question->setData($request)) {
                $feedback_question->save();
                return redirect()->route('Laralum::feedback_questions')->with('success', 'FeedbackQuestion edited successfully.');
            } else {
                return redirect()->route('Laralum::feedback_questions')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the feedback_question, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::feedback_questions')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add feedback_question for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.admin_settings.feedback_questions');
        # Get all the data
        $data_index = 'feedback_questions';
        require('Data/Create/Get.php');

        return view('laralum.feedback_question.create',
            [
                'fields' => $fields,
                'confirmed' => $confirmed,
                'encrypted' => $encrypted,
                'hashed' => $hashed,
                'masked' => $masked,
                'table' => $table,
                'code' => $code,
                'wysiwyg' => $wysiwyg,
                'relations' => $relations,
            ]);
    }

    public function store(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.feedback_questions');
        $rules = FeedbackQuestion::getRules(true);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $feedback_question = FeedbackQuestion::where('question', $request->get("question"))->first();
            if ($feedback_question == null)
                $feedback_question = new FeedbackQuestion();

            if ($feedback_question->setData($request)) {
                $feedback_question->save();
                return redirect()->route('Laralum::feedback-questions')->with('success', 'Feedback Question added successfully.');
            } else {
                return redirect()->route('Laralum::feedback-questions')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the feedback question, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::feedback-questions')->with('error', 'Something went wrong. Please try again later.');
        }

    }


    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.feedback_questions');

        # Select FeedbackQuestion
        $feedback_question = FeedbackQuestion::findOrFail($id);
        # Delete FeedbackQuestion
        $feedback_question->delete();
        # Redirect the admin
        return redirect()->route('Laralum::feedback-questions')->with('success', trans('laralum.msg_feedback_question_deleted'));

    }

    public function printFeedback(Request $request)
    {

        Laralum::permissionToAccess('admin.admin_settings.feedback_questions');

        if ($request->get("question_id") != null) {
            $question = FeedbackQuestion::find($request->get("question_id"));

            $question->update([
                'question' => $request->get("question_" . $question->id)
            ]);
        }

        $feedback_questions = FeedbackQuestion::select('*');

        if (!\Auth::user()->isAdmin()) {
            $feedback_questions = $feedback_questions->where('created_by', \Auth::user()->id);
        }
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        $count = $feedback_questions->count();
        if ($per_page == "All") {
            $pagination = false;
        }
        if ($pagination == true) {
            $feedback_questions = $feedback_questions->paginate($per_page);
        } else {
            $feedback_questions = $feedback_questions->get();
        }

        return view('laralum/feedback_question/print_feedback_question', [
            'feedback_questions' => $feedback_questions,
            'count'=>$count,
            'print' => true,


        ]);
    }


    public function exportFeedback(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.admin_settings.feedback_questions');

        if ($request->get("question_id") != null) {
            $question = FeedbackQuestion::find($request->get("question_id"));

            $question->update([
                'question' => $request->get("question_" . $question->id)
            ]);
        }

        $feedback_questions = FeedbackQuestion::select('*');

        if (!\Auth::user()->isAdmin()) {
            $feedback_questions = $feedback_questions->where('created_by', \Auth::user()->id);
        }
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        $count = $feedback_questions->count();
        if ($per_page == "All") {
            $pagination = false;
        }
        if ($pagination == true) {
            $feedback_questions = $feedback_questions->paginate($per_page);
        } else {
            $feedback_questions = $feedback_questions->get();
        }

        $all_ar[] = [
            'Question',
        ];

        foreach ($feedback_questions as $feedback_question)
        {
            $all_ar[] = [
                $feedback_question->question,
            ];
        }


        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Feedback Questions', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Feedback Questions');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($all_ar) {
                $sheet->fromArray($all_ar, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            /*$pdf = PDF::loadView('booking.pdf', array('data' => $all_ar));
            return $pdf->download('feedback_questions.pdf');*/
            $pdf = SnappyPDF::loadView('booking.pdf', ['data' => $all_ar]);
            return $pdf->download('feedback_questions.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }
}
