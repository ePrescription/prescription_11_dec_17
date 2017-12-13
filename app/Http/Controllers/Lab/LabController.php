<?php

namespace App\Http\Controllers\Lab;

use App\prescription\mapper\LabMapper;
use App\prescription\services\HelperService;
use App\prescription\services\HospitalService;
use App\prescription\services\LabService;
use App\prescription\utilities\Exception\LabException;
use App\prescription\utilities\Exception\AppendMessage;
use App\prescription\common\ResponseJson;
use App\prescription\utilities\ErrorEnum\ErrorEnum;

use App\prescription\facades\HospitalServiceFacade;

use GuzzleHttp\Client;
use App\prescription\model\entities\LabTestDetails;
use App\prescription\common\ResponsePrescription;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\LabReportRequest;

use Exception;
use Log;

use Mail;
use Storage;
use Crypt;

class LabController extends Controller
{
    protected $labService;

    public function __construct(LabService $labService)
    {
        $this->labService = $labService;
    }

    /**
     * Get the profile of the lab
     * @param $labId
     * @throws $labException
     * @return array | null
     * @author Baskaran Subbaraman
     */

    public function getProfile($labId)
    {
        $labProfile = null;
        //dd('Inside get profile function in lab controller');

        try
        {
            $labProfile = $this->labService->getProfile($labId);
            //dd($labProfile);
        }
        catch(LabException $profileExc)
        {
            //dd($hospitalExc);
            $errorMsg = $profileExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($profileExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.lab-profile',compact('labProfile'));

        //return $labProfile;
    }

    /**
     * Get the profile of the lab
     * @param $labId
     * @throws $labException
     * @return array | null
     * @author Vimal
     */

    public function editProfile($labId, HelperService $helperService)
    {
        $labProfile = null;
        $cities = null;
        //dd('Inside get profile function in lab controller');

        try
        {
            $labProfile = $this->labService->getProfile($labId);
            $cities = $helperService->getCities();
            //dd($cities);
        }
        catch(LabException $profileExc)
        {
            //dd($hospitalExc);
            $errorMsg = $profileExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($profileExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.lab-editprofile',compact('labProfile','cities'));

        //return $labProfile;
    }

    /**
     * Get the profile of the lab
     * @param $labId
     * @throws $labException
     * @return array | null
     * @author Vimal
     */

    public function editChangePassword($labId)
    {
        $labProfile = null;
        //dd('Inside get profile function in lab controller');

        try
        {
           // $labProfile = $this->labService->getProfile($labId);
            //dd($labProfile);
        }
        catch(LabException $profileExc)
        {
            //dd($hospitalExc);
            $errorMsg = $profileExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($profileExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.lab-changepassword');

        //return $labProfile;
    }

    /**
     * Get the list of patients for the selected lab
     * @param $labId, $hospitalId
     * @throws $labException
     * @return array | null
     * @author Baskar
     */

    public function getPatientListForLab($labId, $hospitalId)
    {
        $patients = null;

        try
        {
            $patients = $this->labService->getPatientListForLab($labId, $hospitalId);
            //dd($patients);
        }
        catch(LabException $profileExc)
        {
            //dd($hospitalExc);
            $errorMsg = $profileExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($profileExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.lab-patients',compact('patients'));

        //return $patients;
    }

    /**
     * Get the list of lab tests for the selected lab
     * @param $labId, $hospitalId
     * @throws $labException
     * @return array | null
     * @author Baskar
     */

    public function getTestsForLab($labId, $hospitalId)
    {
        $labTests = null;

        try
        {
            $labTests = $this->labService->getTestsForLab($labId, $hospitalId);
            //dd($labTests);
        }
        catch(LabException $profileExc)
        {
            //dd($hospitalExc);
            $errorMsg = $profileExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($profileExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.lab-labtest',compact('labTests'));

        //return $labTests;

    }

    /**
     * Get lab tests by LID
     * @param $lid
     * @throws $labException
     * @return array | null
     * @author Baskar
     */

    public function getLabTestsByLid(Request $lidRequest)
    {
        $labTests = null;
        //dd('Inside labtests by lid');
        $lid = $lidRequest->get('lid');
        //dd($lid);
        try
        {
            $labTests = $this->labService->getLabTestsByLid($lid);
            //dd($labTests);

        }
        catch(LabException $labExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $labExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($labExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

       // return $labTests;

        return view('portal.lab-labtest',compact('labTests'));

        //return $labTests;
    }

    /**
     * Get lab test details for the given lab test id
     * @param $labTestId
     * @throws $labException
     * @return array | null
     * @author Baskar
     */

    public function getLabTestDetails(HospitalService $hospitalService, $labTestId)
    {
        $labTestDetails = null;
        //dd('Inside prescription details');

        try
        {
            $labTestDetails = $hospitalService->getLabTestDetails($labTestId);
            //dd($labTestDetails);

        }
        catch(LabException $labExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $labExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($labExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.lab-labtest-details',compact('labTestDetails'));
    }

    /**
     * Edit Lab Details
     * @param $labRequest
     * @throws $labException
     * @return true | false
     * @author Baskar
     */

    public function editLab(Request $labRequest)
    {
        $labVM = null;
        $status = true;
        $message = null;
        //$string = ""

        try
        {
            //dd('Inside edit lab');
            /*
            $lab = array('lab_name' => 'Anderson Diagnostics', 'address' => 'test', 'city' => 15, 'country' => 99,
                'pincode' => '600005' , 'telephone' => '5464645654', 'email' => 'anderson@gmail.com');
            */
            //dd($labRequest);
            //$pharmacyVM = PharmacyMapper::setPhamarcyDetails($pharmacyRequest);
            $labVM = LabMapper::setLabDetails($labRequest);
            //dd($labVM);
            $status = $this->labService->editLab($labVM);
            //dd($status);

            /*if($status)
            {
                //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_SUCCESS));
            }*/

            if($status) {
                $labId=$labVM->getLabId();
                $labProfile = $this->labService->getProfile($labId);
                $message= "Profile Details Updated Successfully";
            }
        }
        catch(LabException $profileExc)
        {
            //dd($profileExc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR));
            $errorMsg = $profileExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($profileExc);
            Log::error($msg);
            //return $jsonResponse;
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.lab-profile',compact('labProfile','message'));

        //return $jsonResponse;
    }


    /**
     * Get the list of lab tests for the selected patient
     * @param $patientId
     * @throws $labException
     * @return array | null
     * @author Vimal
     */

    public function getTestsForLabForPatient($patientId)
    {
        $labTests = null;

        try
        {
            $labTests = $this->labService->getTestsForLabForPatient($patientId);
            //dd($labTests);
        }
        catch(LabException $profileExc)
        {
            //dd($hospitalExc);
            $errorMsg = $profileExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($profileExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.patient-labtest',compact('labTests'));

        //return $labTests;

    }

    /**
     * Get lab tests by LID
     * @param $lid
     * @throws $labException
     * @return array | null
     * @author Vimal
     */

    public function getLabTestsByLidForPatient(Request $lidRequest)
    {
        $labTests = null;
        //dd('Inside labtests by lid');
        $lid = $lidRequest->get('lid');
        //dd($lid);
        try
        {
            $labTests = $this->labService->getLabTestsByLid($lid);
            //dd($labTests);

        }
        catch(LabException $labExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $labExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($labExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        // return $labTests;

        return view('portal.patient-labtest',compact('labTests'));

        //return $labTests;
    }


    /**
     * Get lab test details for the given lab test id
     * @param $labTestId
     * @throws $labException
     * @return array | null
     * @author Vimal
     */

    public function getLabTestDetailsForPatient(HospitalService $hospitalService, $labTestId)
    {
        $labTestDetails = null;
        //dd('Inside prescription details');

        try
        {
            $labTestDetails = $hospitalService->getLabTestDetails($labTestId);
            //dd($labTestDetails);

        }
        catch(LabException $labExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $labExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($labExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.patient-labtest-details',compact('labTestDetails'));
    }

    /**
     * Forward the lab test details to the specified mail id
     * @param $labTestId, $email
     * @throws $labException
     * @return array | null
     * @author Baskar
     */

    public function forwardLabDetailsByMail(HospitalService $hospitalService, $labTestId, $email)
    {
        $labTestDetails = null;
        $labMailInfo = null;
        //dd('Inside prescription details');

        try
        {
            $labTestDetails = $hospitalService->getLabTestDetails($labTestId);
            //dd($labTestDetails);

            $subject = "LabTest Details";
            $name = "ePrescription and Lab Tests Application";
            $title = "LabTest Details";
            $content = $labTestDetails;
            $to = $email;

            $data = array('name' => $name, 'title' => $title, 'labTestDetails' => $labTestDetails);

            Mail::send('emails.labtest', $data, function ($m) use($to, $subject)  {
                //$m->from('prescriptionapp1@gmail.com', $name);
                //$m->to($to)->subject($subject);
                $m->from('shamprasadp26@gmail.com', 'ePrescription and Lab Tests Application');;
                //$m->to('alagirivimal@gmail.com')->subject('ePrescription and Lab Tests Application');
                $m->to($to)->subject($subject);
            });

            $labMailInfo = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::LAB_DETAILS_SUCCESS));
            $labMailInfo->setObj("Mail Sent Successfully");
        }
        catch(LabException $labExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_DETAILS_ERROR));
            $errorMsg = $labExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($labExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $labMailInfo;
        //return view('portal.patient-labtest-details',compact('labTestDetails'));
    }

    /**
     * Forward the lab test details by SMS
     * @param $labTestId, $mobileNumber
     * @throws $labException
     * @return array | null
     * @author Baskar
     */

    public function forwardLabDetailsBySMS(HospitalService $hospitalService, $labTestId, $mobile)
    {
        $labTestDetails = null;
        //dd('Inside prescription details');

        $labTestSMS = null;
        $responseJson = null;

        try
        {
            $labTestDetails = $hospitalService->getLabTestDetails($labTestId);
            //dd($labTestDetails);

            $patientName = $labTestDetails['PatientProfile'][0]->name;
            $doctorName = $labTestDetails['DoctorProfile'][0]->name;
            $hospitalName = $labTestDetails['HospitalProfile'][0]->hospital_name;
            $labTestDate = $labTestDetails['PatientProfile'][0]->labtest_date;
            //$mobile = $prescriptionDetails['HospitalProfile'][0]->telephone;

            $labTests = $labTestDetails['PatientLabTestDetails'];

            foreach($labTests as $labTest)
            {
                $labTestSMS .= "Lab Test Name: ".$labTest->test_name."%0a"."Lab Test Category: ".$labTest->test_category;
                //dd($labTestSMS);
            }

            $message = "Patient Name : ".$patientName."%0a"
                ." Doctor Name: ".$doctorName."%0a"
                ." Hospital Name: ".$hospitalName."%0a"
                ." Lab Test Id: ".$labTestId."%0a"
                ." Lab Test Date: ".$labTestDate."%0a"
                ." Lab Tests: ".$labTestSMS;

            $client = new Client();
            $response = $client->get('http://bhashsms.com/api/sendmsg.php?user=Daiwiksoft&pass=Daiwik2612&sender=daiwik&phone='.$mobile.'&text='.$message.'&priority=ndnd&stype=normal');

            if($response->getStatusCode() == 200)
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::LABTEST_SMS_SUCCESS));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LABTEST_SMS_ERROR));
            }

            $responseJson->sendSuccessResponse();

            //$labSMSInfo = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::LAB_DETAILS_SUCCESS));
            //$labSMSInfo->setObj("SMS Sent Successfully");

        }
        catch(LabException $labExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_DETAILS_ERROR));
            $responseJson->sendErrorResponse($labExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_DETAILS_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
        //return view('portal.patient-labtest-details',compact('labTestDetails'));
    }



    public function getTestsForDoctor($doctorId, $hospitalId)
    {

        $labTests = null;

        try
        {
            $labTests = $this->labService->getTestsForDoctor($doctorId, $hospitalId);
            //dd($labTests);
        }
        catch(LabException $profileExc)
        {
            //dd($hospitalExc);
            $errorMsg = $profileExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($profileExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.doctor-labtest',compact('labTests'));

        //return $labTests;

    }


    public function getLabTestDetailsForDoctor(HospitalService $hospitalService, $labTestId)
    {
        $labTestDetails = null;
        //dd('Inside prescription details');

        try
        {
            $labTestDetails = $hospitalService->getLabTestDetails($labTestId);
            //dd($labTestDetails);

        }
        catch(LabException $labExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $labExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($labExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.doctor-labtest-details',compact('labTestDetails'));
    }


    public function getLabTestDetailsForHospital(HospitalService $hospitalService, $labTestId)
    {
        $labTestDetails = null;
        //dd('Inside prescription details');

        try
        {
            $labTestDetails = $hospitalService->getLabTestDetails($labTestId);
            //dd($labTestDetails);

        }
        catch(LabException $labExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $labExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($labExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.hospital-labtest-details',compact('labTestDetails'));
    }

    /**
     * Upload patient lab test documents
     * @param $uploadRequest
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function uploadPatientLabDocuments(Request $uploadRequest)
    {
        $status = true;
        $labDocumentsVM = null;
        //dd('Inside lab controller');

        try
        {
            $labDocumentsVM = LabMapper::setLabDocumentDetails($uploadRequest);
            $status = $this->labService->uploadPatientLabDocuments($labDocumentsVM);

            if($status)
            {
                return redirect('lab/'.$uploadRequest->lab_id.'/hospital/'.$uploadRequest->hospital_id.'/patient/'.$uploadRequest->patient_id.'/lab-report-upload')->with('success','Lab Report Upload Successfully');
            }
            else
            {
                return redirect('lab/'.$uploadRequest->lab_id.'/hospital/'.$uploadRequest->hospital_id.'/patient/'.$uploadRequest->patient_id.'/lab-report-upload')->with('message','Lab Report Upload Issues');
            }
        }
        catch(LabException $userExc)
        {
            //dd($userExc);
            $errorMsg = $userExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($userExc);
            Log::error($msg);
            //return redirect('exception')->with('message',$errorMsg." ".trans('messages.SupportTeam'));
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
            //return redirect('exception')->with('message',trans('messages.SupportTeam'));
        }

    }
     public function uploadPatientLabDocumentsnew(Request $uploadRequest)
    {
        $status = true;
        $labDocumentsVM = null;
        //dd('Inside lab controller');

        try
        {
            $labDocumentsVM = LabMapper::setLabDocumentDetails($uploadRequest);
            $status = $this->labService->uploadPatientLabDocuments($labDocumentsVM);

            if($status)
            {
                return redirect('lab/'.$uploadRequest->lab_id.'/hospital/'.$uploadRequest->hospital_id.'/patient/'.$uploadRequest->patient_id.'/lab-report-upload')->with('success','Lab Report Upload Successfully');
            }
            else
            {
                return redirect('lab/'.$uploadRequest->lab_id.'/hospital/'.$uploadRequest->hospital_id.'/patient/'.$uploadRequest->patient_id.'/lab-report-upload')->with('message','Lab Report Upload Issues');
            }
        }
        catch(LabException $userExc)
        {
            //dd($userExc);
            $errorMsg = $userExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($userExc);
            Log::error($msg);
            //return redirect('exception')->with('message',$errorMsg." ".trans('messages.SupportTeam'));
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
            //return redirect('exception')->with('message',trans('messages.SupportTeam'));
        }

    }

    /**
     * Get patient documents
     * @param $patientId
     * @throws $labException
     * @return true | false
     * @author Baskar
     */

    public function getPatientDocuments($doctorId, $hospitalId, $patientId)
    {
        $labReports = null;

        try
        {
            $patientDetails = HospitalServiceFacade::getPatientProfile($patientId);
            $labReports = $this->labService->getPatientDocuments($patientId);
            //dd($labReports);
            //dd($patientDetails);
        }
        catch(LabException $userExc)
        {
            //dd($userExc);
            $errorMsg = $userExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($userExc);
            Log::error($msg);
            //return redirect('exception')->with('message',$errorMsg." ".trans('messages.SupportTeam'));
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
            //return redirect('exception')->with('message',trans('messages.SupportTeam'));
        }
        return view('portal.lab-patient-lab-report-download',compact('patientDetails','labReports'));
    }


    public function getPatientDocumentsForDoctor($doctorId, $hospitalId, $patientId)
    {
        $labReports = null;

        try
        {
            $patientDetails = HospitalServiceFacade::getPatientProfile($patientId);
            $labReports = $this->labService->getPatientDocuments($patientId);
            //dd($labReports);
            //dd($patientDetails);
        }
        catch(LabException $userExc)
        {
            //dd($userExc);
            $errorMsg = $userExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($userExc);
            Log::error($msg);
            //return redirect('exception')->with('message',$errorMsg." ".trans('messages.SupportTeam'));
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
            //return redirect('exception')->with('message',trans('messages.SupportTeam'));
        }
        return view('portal.doctor-patient-lab-report-download',compact('patientDetails','labReports'));
    }

    public function getPatientDocumentsForHospital($hospitalId, $patientId)
    {
        $labReports = null;

        try
        {
            $patientDetails = HospitalServiceFacade::getPatientProfile($patientId);
            $labReports = $this->labService->getPatientDocuments($patientId);
            //dd($labReports);
            //dd($patientDetails);
        }
        catch(LabException $userExc)
        {
            //dd($userExc);
            $errorMsg = $userExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($userExc);
            Log::error($msg);
            //return redirect('exception')->with('message',$errorMsg." ".trans('messages.SupportTeam'));
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
            //return redirect('exception')->with('message',trans('messages.SupportTeam'));
        }
        return view('portal.hospital-patient-lab-report-download',compact('patientDetails','labReports'));
    }

    /**
     * Download document item
     * @param $documentItemId
     * @throws $labException
     * @return true | false
     * @author Baskar
     */

    public function downloadPatientDocument($documentItemId)
    {
        //dd($documentItemId);
        $documentItem = null;
        $contents = null;
        $path = null;
        $documentName = null;

        try
        {
            $documentItem = $this->labService->downloadPatientDocument($documentItemId);
            //dd($documentItem);
            if(!empty($documentItem))
            {
                $downloadFileName = $documentItem[0]->document_path;

                /*
                echo $path = storage_path($downloadFileName);
                return response()->download($path);
                */

                //$path =  Config::get('constants.DOCTOR_EMPANEL_URL') .'/' .$downloadFileName;
                $path = storage_path('app').'/' .$downloadFileName;
                $file = Storage::disk('local')->get($downloadFileName);
                $contents = Crypt::decrypt($file);

                //$documentName = $documentItem[0]->test_category_name.'-'.$documentItem[0]->document_name.'.'.pathinfo($path, PATHINFO_EXTENSION);
                $documentName = $documentItem[0]->document_name.' '.$documentItem[0]->document_upload_date.'.'.pathinfo($path, PATHINFO_EXTENSION);
            }

        }
        catch(LabException $userExc)
        {
            //dd($userExc);
            $errorMsg = $userExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($userExc);
            Log::error($msg);
            //return redirect('exception')->with('message',$errorMsg." ".trans('messages.SupportTeam'));
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
            //return redirect('exception')->with('message',trans('messages.SupportTeam'));
        }

        return response()->make($contents, 200, array(
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $documentName . '"'
        ));

        /*$documentName = $documentItem[0]->test_category_name.'-'.$documentItem[0]->document_name.'.'.pathinfo($path, PATHINFO_EXTENSION);
        return response()->make($contents, 200, array(
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $documentName . '"'
        ));*/


        /*
        return response()->make($contents, 200, array(
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . pathinfo($path, PATHINFO_BASENAME) . '"'
        ));
        */

    }


    public function getLabTestUploadForLab($labTestId)
    {
        //dd('Inside prescription details');
        return view('portal.lab-labtest-upload',compact('labTestId'));
    }

    public function getLabTestUploadSaveForLab(LabReportRequest $labTestUpload)
    {
        //dd($labTestUpload->input('labTestId'));
        //dd('Inside lab upload details');
        $labTestId = $labTestUpload->input('labTestId');
        $file = $labTestUpload->file('labtest_report');
        //dd($file);
        //Display File Name
        echo 'File Name: '.$file->getClientOriginalName();
        echo '<br>';

        //Display File Extension
        echo 'File Extension: '.$file->getClientOriginalExtension();
        echo '<br>';

        //Display File Real Path
        echo 'File Real Path: '.$file->getRealPath();
        echo '<br>';

        //Display File Size
        echo 'File Size: '.$file->getSize();
        echo '<br>';

        //Display File Mime Type
        echo 'File Mime Type: '.$file->getMimeType();

        //Move Uploaded File
        $destinationPath = 'uploads/'.$labTestId;
        $file->move($destinationPath,$file->getClientOriginalName());


        $domain = "ec2-50-112-212-39.us-west-2.compute.amazonaws.com";

        $LabTestDetails = LabTestDetails::find($labTestId);
        //dd($LabTestDetails);
        $LabTestDetails->labtest_report = $domain.'/'.$destinationPath.'/'.$file->getClientOriginalName();

        $LabTestDetails->save();

        $msg = "Lab Report Upload.";
        return redirect('lab/rest/api/lab/23')->with('message',$msg);
       // return view('portal.lab-labtest-upload',compact('labTestId'));
    }


    public function PatientDetailsForLab($lid,$hid,$patientId)
    {
        $patientDetails = null;
        $patientPrescriptions = null;
        $labTests = null;
        //$jsonResponse = null;
        //dd('Inside patient details');
        try
        {
            //$patientDetails = HospitalServiceFacade::getPatientDetailsById($patientId);
            $patientDetails = HospitalServiceFacade::getPatientProfile($patientId);
            $labTests = HospitalServiceFacade::getLabTestsByPatient($patientId);
            //$patientAppointment = HospitalServiceFacade::getPatientAppointments($patientId);
            //$patientAppointment = HospitalServiceFacade::getPatientAppointmentsByHospital($patientId, $hid);
            //dd($patientDetails);
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_DETAILS_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.lab-patient-details',compact('patientDetails','labTests'));

    }


    public function PatientLabDetailsForLab($lid,$hid,$patientId)
    {
        $patientDetails = null;
        $patientPrescriptions = null;
        $labTests = null;
        $patientAppointment = null;
        //$jsonResponse = null;
        //dd('Inside patient details');
        try
        {
            $patientDetails = HospitalServiceFacade::getPatientProfile($patientId);
            $patientExaminations = HospitalServiceFacade::getExaminationDates($patientId, $hid);
            //dd($patientExaminations);
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_DETAILS_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.lab-patient-lab-details',compact('patientExaminations','patientDetails'));

    }


    public function PatientLabReportUploadForLab($lid,$hid,$patientId)
    {
        $patientDetails = null;
        $patientPrescriptions = null;
        $labTests = null;
        $patientAppointment = null;
        //$jsonResponse = null;
        //dd('Inside patient details');
        try
        {
            $patientDetails = HospitalServiceFacade::getPatientProfile($patientId);
            $patientExaminations = HospitalServiceFacade::getExaminationDates($patientId, $hid);
            //dd($patientExaminations);
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_DETAILS_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.lab-patient-lab-report-upload',compact('patientDetails'));

    }

public function PatientLabReportEntryForLab($lid,$hid,$patientId)
    {
        $patientDetails = null;
        $patientPrescriptions = null;
        $labTests = null;
        $patientAppointment = null;
        //$jsonResponse = null;
        //dd('Inside patient details');
        try
        {
            $patientDetails = HospitalServiceFacade::getPatientProfile($patientId);
            $patientExaminations = HospitalServiceFacade::getExaminationDates($patientId, $hid);
            //dd($patientExaminations);
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_DETAILS_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.lab-patient-lab-report-entry',compact('patientDetails'));

    }
}
