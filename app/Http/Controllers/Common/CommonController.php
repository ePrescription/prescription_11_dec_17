<?php

namespace App\Http\Controllers\Common;

use App\prescription\services\HelperService;
use App\prescription\services\HospitalService;
use App\prescription\facades\HospitalServiceFacade;
use App\prescription\utilities\Exception\HelperException;
use App\prescription\utilities\Exception\HospitalException;
use App\prescription\utilities\Exception\AppendMessage;
use App\prescription\common\ResponseJson;
use App\prescription\utilities\ErrorEnum\ErrorEnum;

use App\prescription\common\ResponsePrescription;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Log;
use Exception;

class CommonController extends Controller
{
    protected $hospitalService;

    public function __construct(HospitalService $hospitalService)
    {
        $this->hospitalService = $hospitalService;
    }

    /**
     * Get list of hospitals by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getHospitalByKeyword($keyword = null)
    {
        $hospitals = null;
        $responseJson = null;

        try
        {
            $hospitals = $this->hospitalService->getHospitals();
            //$hospitalsList = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_LIST_SUCCESS));
            //$hospitalsList->setObj($hospitals);

            if(!empty($hospitals))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_LIST_SUCCESS));
                $responseJson->setCount(sizeof($hospitals));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_HOSPITAL_LIST_FOUND));
            }

            $responseJson->setObj($hospitals);
            $responseJson->sendSuccessResponse();
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            //$prescriptionResult = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_LIST_ERROR));
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
            //$errorMsg = $hospitalExc->getMessageForCode();
            //$msg = AppendMessage::appendMessage($hospitalExc);
            //Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
            //$msg = AppendMessage::appendGeneralException($exc);
            //Log::error($msg);
        }

        return $responseJson;
    }

    /**
     * Get profile of patient
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientProfile($patientId)
    {
        $patientProfile = null;
        $responseJson = null;

        try
        {
            //dd('Inside function');
            $patientProfile = $this->hospitalService->getPatientProfile($patientId);
            //dd($patientProfile);
            if(!empty($patientProfile))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_LIST_SUCCESS));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_PATIENT_LIST_FOUND));
            }

            $responseJson->setObj($patientProfile);
            $responseJson->sendSuccessResponse();
            //dd($patientProfile);
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            //$prescriptionResult = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
            //$msg = AppendMessage::appendGeneralException($exc);
            //Log::error($msg);
        }

        return $responseJson;
    }

    /**
     * Get profile of patient
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function searchPatientByPid(Request $patientPidRequest)
    {
        $patient = null;
        $pid = $patientPidRequest->get('pid');
        //return $pid;

        try
        {
            $patient = $this->hospitalService->searchPatientByPid($pid);

            if(!empty($patient))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_DETAILS_SUCCESS));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_PATIENT_DETAILS_FOUND));
            }

            $responseJson->setObj($patient);
            $responseJson->sendSuccessResponse();
        }
        catch(HospitalException $hospitalExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            /*$errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);*/
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_DETAILS_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            /*$msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);*/
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_DETAILS_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }


        return $responseJson;
    }

    /* Get all the cities
     * @params none
     * @throws HelperException
     * @return array | null
     * @author Baskar
     */

    public function getCities(HelperService $helperService)
    {
        $cities = null;

        try
        {
            $cities = $helperService->getCities();
        }
        catch(HelperException $cityExc)
        {
            $errorMsg = $cityExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($cityExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $cities;
    }
}
