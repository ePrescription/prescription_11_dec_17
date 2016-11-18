<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/8/2016
 * Time: 5:09 PM
 */

namespace App\prescription\repositories\repoimpl;

use App\Http\ViewModels\NewAppointmentViewModel;
use App\Http\ViewModels\PatientLabTestViewModel;
use App\Http\ViewModels\PatientProfileViewModel;
use App\prescription\model\entities\DoctorAppointments;
use App\prescription\model\entities\Hospital;
use App\prescription\model\entities\LabTestDetails;
use App\prescription\model\entities\Patient;
use App\prescription\model\entities\PatientLabTests;
use App\prescription\model\entities\PatientPrescription;
use App\prescription\model\entities\PrescriptionDetails;
use App\prescription\repositories\repointerface\HospitalInterface;
use App\prescription\utilities\ErrorEnum\ErrorEnum;
use App\prescription\utilities\Exception\HospitalException;
use App\Http\ViewModels\PatientPrescriptionViewModel;

use App\prescription\utilities\UserType;
use App\User;
use App\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Exception;


class HospitalImpl implements HospitalInterface{

    /**
     * Get list of hospitals
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getHospitals()
    {
        $hospitals = null;

        try
        {
            $query = DB::table('hospital as h')->select('h.id', 'h.hospital_id', 'h.hospital_name',
                DB::raw('CONCAT(h.address, " ", c.city_name, " ", co.name) as hospital_details'));
                //'h.address as hospital_details', 'c.city_name', 'co.name as country');
            $query->join('users as u', 'u.id', '=', 'h.hospital_id');
            $query->join('cities as c', 'c.id', '=', 'h.city');
            $query->join('countries as co', 'co.id', '=', 'h.country');
            $query->where('u.delete_status', '=', 1);

            $hospitals = $query->get();

            dd($hospitals);
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_LIST_ERROR, $exc);
        }

        //dd($hospitals);
        return $hospitals;
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


        try
        {
            $query = DB::table('hospital as h')->distinct()->select('h.id', 'h.hospital_id', 'h.hospital_name',
                'h.address', 'c.city_name', 'co.name as country');
            $query->join('users as u', 'u.id', '=', 'h.hospital_id');
            $query->join('cities as c', 'c.id', '=', 'h.city');
            $query->join('countries as co', 'co.id', '=', 'h.country');
            $query->where('h.hospital_name', 'LIKE', '%'.$keyword.'%');
            $query->where('u.delete_status', '=', 1);

            //$query->distinct()
            $hospitals = $query->get();
            //dd($hospitals);
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_LIST_ERROR, $exc);
        }

        return $hospitals;
    }

    //Get Appointment details

    /**
     * Get list of appointments for the hospital and the doctor
     * @param $hospitalId, $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getAppointmentsByHospitalAndDoctor($hospitalId, $doctorId)
    {
        $appointments = null;

        try
        {
            $query = DB::table('doctor_appointment as da')->select('da.id', 'da.patient_id',
                'p.name as patient_name', 'p.pid',
                'da.hospital_id', 'h.hospital_name',
                'da.doctor_id', 'd.name as doctor_name',
                'da.appointment_date', 'da.appointment_time');
            $query->join('hospital as h', 'h.hospital_id', '=', 'da.hospital_id');
            $query->join('patient as p', 'p.patient_id', '=', 'da.patient_id');
            $query->join('doctor as d', 'd.doctor_id', '=', 'da.doctor_id');
            $query->where('da.hospital_id', '=', $hospitalId);
            $query->where('da.doctor_id', '=', $doctorId);

            $appointments = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::APPOINTMENT_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::APPOINTMENT_LIST_ERROR, $exc);
        }

        return $appointments;
    }

    /**
     * Get list of doctors for the hospital
     * @param $hospitalId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getDoctorsByHospitalId($hospitalId)
    {
        $doctors = null;

        try
        {
            $query = DB::table('hospital_doctor as hd')->join('users as usr1', 'usr1.id', '=', 'hd.hospital_id');
            $query->join('users as usr2', 'usr2.id', '=', 'hd.doctor_id');
            $query->join('doctor as d', 'd.doctor_id', '=', 'hd.doctor_id');
            $query->where('usr2.delete_status', '=', 1);
            $query->select('d.id as id', 'd.doctor_id as doctorId', 'd.name as doctorName', 'd.did as doctorUniqueId');

            $doctors = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_DOCTOR_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_DOCTOR_LIST_ERROR, $exc);
        }

        return $doctors;
    }

    //Get Patient List
    /**
     * Get list of patients for the hospital and patient name
     * @param $hospitalId, $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    //public function getPatientsByHospital($hospitalId, $keyword)
    public function getPatientsByHospital($hospitalId)
    {
        $patients = null;

        try
        {
            $query = DB::table('hospital_patient as hp')->select('p.id', 'p.patient_id', 'p.pid', 'p.name', 'p.gender', 'p.telephone',
                            'h.hospital_id', 'h.hospital_name');
            $query->join('hospital as h', 'h.hospital_id', '=', 'hp.hospital_id');
            $query->join('patient as p', 'p.patient_id', '=', 'hp.patient_id');
            $query->where('hp.hospital_id', '=', $hospitalId);
            //$query->where('p.name', 'LIKE', '%'.$keyword.'%');

            //dd($query->toSql());
            $patients = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patients;
    }

    //Get Patient Details
    /**
     * Get patient details by patient id
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientDetailsById($patientId)
    {
        $patientDetails = null;

        try
        {
            $query = DB::table('patient as p')->select('p.id', 'p.name', 'p.address','p.pid', 'c.city_name',
                            'co.name','p.telephone', 'p.email',
                            'p.dob', 'p.age', 'p.place_of_birth', 'p.nationality', 'p.gender',
                            'da.appointment_date', 'da.appointment_time');
            $query->join('doctor_appointment as da', 'da.patient_id', '=', 'p.patient_id');
            $query->join('cities as c', 'c.id', '=', 'p.city');
            $query->join('countries as co', 'co.id', '=', 'p.country');
            $query->where('p.patient_id', '=', $patientId);

            $patientDetails = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_DETAILS_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_DETAILS_ERROR, $exc);
        }

        return $patientDetails;
    }

    //Get Patient Profile
    /**
     * Get patient profile by patient id
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientProfile($patientId)
    {
        $patientProfile = null;

        try
        {
            $query = DB::table('patient as p')->select('p.id', 'p.patient_id', 'p.name', 'p.pid', 'p.age', 'p.gender', 'p.telephone');
            $query->join('users as usr', 'usr.id', '=', 'p.patient_id');
            $query->where('p.patient_id', '=', $patientId);
            $query->where('usr.delete_status', '=', 1);

            $patientProfile = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_PROFILE_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_PROFILE_ERROR, $exc);
        }

        return $patientProfile;
    }

    //Get Prescription List
    /**
     * Get list of prescriptions for the selected hospital and doctor
     * @param $hospitalId, $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPrescriptions($hospitalId, $doctorId)
    {
        $prescriptions = null;

        try
        {
            $query = DB::table('patient as p')->select('pp.id as prescription_id', 'pp.unique_id as unique_id', 'p.patient_id', 'p.pid', 'p.name', 'pp.prescription_date');
            $query->join('patient_prescription as pp', 'pp.patient_id', '=', 'p.patient_id');
            $query->where('pp.hospital_id', '=', $hospitalId);
            $query->where('pp.doctor_id', '=', $doctorId);
            $query->orderBy('pp.id', 'DESC');

            $prescriptions = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $exc);
        }

        return $prescriptions;
    }

    /**
     * Get list of prescriptions for the patient
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPrescriptionByPatient($patientId)
    {
        $prescriptions = null;

        try
        {
            $query = DB::table('patient as p')->select('pp.id as prescription_id', 'pp.unique_id as unique_id', 'pp.patient_id', 'p.pid', 'p.name', 'pp.prescription_date');
            $query->join('patient_prescription as pp', 'pp.patient_id', '=', 'p.patient_id');
            $query->where('pp.patient_id', '=', $patientId);
            $query->orderBy('pp.id', 'DESC');

            $prescriptions = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $exc);
        }

        return $prescriptions;
    }

    /**
     * Get prescription details for the patient by prescription Id
     * @param $prescriptionId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPrescriptionDetails($prescriptionId)
    {
        $prescriptionDetails = null;
        $patientDetails = null;
        $doctorDetails = null;
        $patientPrescription = null;

        try
        {
            /*$query = DB::table('patient as p')->select('p.patient_id', 'p.name', 'p.pid', 'p.telephone',
                'd.brand_name', 'pd.dosage', 'pd.no_of_days', 'pd.morning', 'pd.afternoon', 'pd.night');
            $query->join('patient_prescription as pp', 'pp.patient_id', '=', 'p.patient_id');
            $query->join('prescription_details as pd', 'pd.patient_prescription_id', '=', 'pp.id');
            $query->join('drugs as d', 'd.id', '=', 'pd.drug_id');
            $query->where('pp.id', '=', $prescriptionId);*/

            $patientQuery = DB::table('patient as p')->select('p.id', 'p.patient_id', 'p.name', 'p.pid', 'p.telephone', 'p.email');
            $patientQuery->join('patient_prescription as pp', 'pp.patient_id', '=', 'p.patient_id');
            $patientQuery->where('pp.id', '=', $prescriptionId);
            $patientDetails = $patientQuery->get();

            $doctorQuery = DB::table('doctor as d')->select('d.id', 'd.doctor_id', 'd.name', 'd.did', 'd.telephone', 'd.email');
            $doctorQuery->join('patient_prescription as pp', 'pp.doctor_id', '=', 'd.doctor_id');
            $doctorQuery->where('pp.id', '=', $prescriptionId);

            $doctorDetails = $doctorQuery->get();

            $query = DB::table('prescription_details as pd')->select('b.id as brand_id', 'b.brand_name', 'd.id as drug_id',
                        'd.drug_name',
                        'pd.dosage', 'pd.no_of_days',
                        'pd.morning', 'pd.afternoon', 'pd.night');
            $query->join('patient_prescription as pp', 'pp.id', '=', 'pd.patient_prescription_id');
            $query->join('brands as b', 'b.id', '=', 'pd.brand_id');
            $query->join('drugs as d', 'd.id', '=', 'pd.drug_id');
            $query->where('pp.id', '=', $prescriptionId);

            $prescriptionDetails = $query->get();

            $patientPrescription["PatientProfile"] = $patientDetails;
            $patientPrescription["DoctorProfile"] = $doctorDetails;
            $patientPrescription["PatientDrugDetails"] = $prescriptionDetails;

        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_DETAILS_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_DETAILS_ERROR, $exc);
        }

        return $patientPrescription;
    }

    /**
     * Save Prescription for the patient
     * @param
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientPrescription(PatientPrescriptionViewModel $patientPrescriptionVM)
    {
        $status = true;
        $patientPrescription = null;

        try
        {
            $doctorId = $patientPrescriptionVM->getDoctorId();
            $patientId = $patientPrescriptionVM->getPatientId();
            $hospitalId = $patientPrescriptionVM->getHospitalId();

            $doctorUser = User::find($doctorId);
            $hospitalUser = User::find($hospitalId);
            $patientUser = User::find($patientId);

            if (!is_null($doctorUser) && !is_null($hospitalUser) && !is_null($patientUser))
            {
                $patientPrescription = new PatientPrescription();
                $patientPrescription->hospital_id = $hospitalId;
                $patientPrescription->doctor_id = $doctorId;
                $patientPrescription->brief_description = $patientPrescriptionVM->getBriefDescription();
                //$patientPrescription->unique_id = "PRID".time();
                $patientPrescription->unique_id = 'PRID'.crc32(uniqid(rand()));
                $patientPrescription->prescription_date = $patientPrescriptionVM->getPrescriptionDate();
                $patientPrescription->created_by = 'Admin';
                $patientPrescription->modified_by = 'Admin';
                $patientUser->prescriptions()->save($patientPrescription);
            }

            $this->savePrescriptionDetails($patientPrescription, $patientPrescriptionVM);
        }
        catch(QueryException $queryEx)
        {
            $status = false;
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            $status = false;
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR, $exc);
        }

        return $status;
    }

    private function savePrescriptionDetails($patientPrescription, PatientPrescriptionViewModel $patientPrescriptionVM)
    {
        $drugs = $patientPrescriptionVM->getDrugDetails();
        foreach($drugs as $drug)
        {
            $prescriptionDetails = new PrescriptionDetails();

            $drugObj = (object) $drug;
            $prescriptionDetails->drug_id = $drugObj->drugId;
            $prescriptionDetails->brand_id = $drugObj->brandId;
            $prescriptionDetails->brief_description = "Test";
            $prescriptionDetails->dosage = $drugObj->dosage;
            $prescriptionDetails->no_of_days = $drugObj->noOfDays;
            $prescriptionDetails->morning = $drugObj->morning;
            $prescriptionDetails->afternoon = $drugObj->afternoon;
            $prescriptionDetails->night = $drugObj->night;
            $prescriptionDetails->created_by = 'Admin';
            $prescriptionDetails->modified_by = 'Admin';

            $patientPrescription->prescriptiondetails()->save($prescriptionDetails);
        }
    }

    //Search by Patient Name
    /**
     * Get patient names by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function searchPatientByName($keyword)
    {
        $patientNames = null;

        try
        {
            $query = DB::table('patient as p')->select('p.id', 'p.patient_id', 'p.name', 'p.pid');
            $query->join('users as usr', 'usr.id', '=', 'p.patient_id');
            $query->where('usr.delete_status', '=', 1);
            $query->where('p.name', 'LIKE', $keyword.'%');

            //dd($query->toSql());
            $patientNames = $query->get();

        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patientNames;
    }

    //Search by Patient Pid
    /**
     * Get patient details by PID
     * @param $pid
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function searchPatientByPid($pid)
    {
        $patient = null;

        try
        {
            $query = DB::table('patient as p')->select('p.id', 'p.patient_id', 'p.name', 'p.pid', 'p.telephone');
            $query->join('users as usr', 'usr.id', '=', 'p.patient_id');
            $query->where('usr.delete_status', '=', 1);
            $query->where('p.pid', 'LIKE', '%'.$pid.'%');

            //dd($query->toSql());

            $patient = $query->get();
            //dd($patient);
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patient;
    }

    /**
     * Get patient by Pid or Name
     * @param $keyWord
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function searchByPatientByPidOrName($keyWord = null)
    {
        $patients = null;

        try
        {
            $query = DB::table('patient as p')->select('p.id', 'p.patient_id', 'p.name', 'p.pid', 'p.telephone');
            $query->join('users as usr', 'usr.id', '=', 'p.patient_id');
            $query->where('usr.delete_status', '=', 1);
            $query->where('p.pid', 'LIKE', '%'.$keyWord.'%');
            $query->orWhere('p.name', 'LIKE', '%'.$keyWord.'%');

            //dd($query->toSql());
            $patients = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patients;
    }

    /**
     * Save new appointments for the patient
     * @param $newAppointmentVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function saveNewAppointment(NewAppointmentViewModel $appointmentVM)
    {
        $status = true;
        $patientPrescription = null;

        try
        {
            $doctorId = $appointmentVM->getDoctorId();
            $patientId = $appointmentVM->getPatientId();
            $hospitalId = $appointmentVM->getHospitalId();

            $doctorUser = User::find($doctorId);
            $hospitalUser = User::find($hospitalId);
            $patientUser = User::find($patientId);

            if (!is_null($doctorUser) && !is_null($hospitalUser) && !is_null($patientUser))
            {
                $appointment = new DoctorAppointments();
                $appointment->patient_id = $patientId;
                $appointment->hospital_id = $hospitalId;
                $appointment->brief_history = $appointmentVM->getBriefHistory();
                $appointment->appointment_date = $appointmentVM->getAppointmentDate();
                $appointment->appointment_time = $appointmentVM->getAppointmentTime();
                $appointment->created_by = $appointmentVM->getCreatedBy();
                $appointment->modified_by = $appointmentVM->getUpdatedBy();
                $appointment->created_at = $appointmentVM->getCreatedAt();
                $appointment->updated_at = $appointmentVM->getUpdatedAt();

                $doctorUser->appointments()->save($appointment);
            }

        }
        catch(QueryException $queryEx)
        {
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_NEW_APPOINTMENT_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_NEW_APPOINTMENT_ERROR, $exc);
        }

        return $status;
    }

    //Drugs
    /**
     * Get brand names by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getBrandNames($keyword)
    {
        $brands = null;
        //dd($keyword);

        try
        {
            $query = DB::table('brands as b')->select('b.id as brand_id', 'b.brand_name', 'd.id as drug_id', 'd.drug_name');
            $query->join('drugs as d', 'd.id', '=', 'b.drug_id');
            $query->where('b.brand_name', 'LIKE', $keyword.'%');
            $query->where('b.brand_status', '=', 1);
            //dd($query->toSql());
            $brands = $query->get();
            //dd($brands);
            /*$query = DB::table('drugs as d')->select('d.id', 'd.brand_name', 'd.drug_name');
            $query->where('d.brand_name', 'LIKE', $keyword.'%');
            $query->where('d.drug_status', '=', 1);

            $brands = $query->get();*/
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::BRAND_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::BRAND_LIST_ERROR, $exc);
        }

        return $brands;
    }

    //Lab Tests
    /**
     * Get all lab tests
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getLabTests()
    {
        $labTests = null;

        try
        {
            $query = DB::table('labtest as lt')->select('lt.id', 'lt.test_name')->where('lt.test_status', '=', 1);
            $labTests = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::LAB_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::LAB_LIST_ERROR, $exc);
        }

        return $labTests;
    }

    /**
     * Get list of lab tests for the selected hospital and doctor
     * @param $hospitalId, $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getLabTestsForPatient($hospitalId, $doctorId)
    {
        $patientLabTests = null;

        try
        {
            $query = DB::table('patient as p')->select('pl.id as labtest_id', 'pl.unique_id as unique_id', 'p.patient_id', 'p.pid', 'p.name', 'pl.labtest_date');
            $query->join('patient_labtest as pl', 'pl.patient_id', '=', 'p.patient_id');
            $query->where('pl.hospital_id', '=', $hospitalId);
            $query->where('pl.doctor_id', '=', $doctorId);
            $query->orderBy('pl.id', 'DESC');

            $patientLabTests = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::LAB_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::LAB_LIST_ERROR, $exc);
        }

        return $patientLabTests;
    }

    /**
     * Get list of labtests for the patient
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getLabTestsByPatient($patientId)
    {
        $labTests = null;

        try
        {
            /*$query = DB::table('patient as p')->select('pl.id as labtest_id','pl.unique_id as unique_id', 'pl.patient_id', 'p.pid', 'p.name', 'l.test_name', 'pl.labtest_date');
            $query->join('patient_labtest as pl', 'pl.patient_id', '=', 'p.patient_id');
            $query->join('labtest_details as ld', 'ld.patient_labtest_id', '=', 'pl.id');
            $query->join('labtest as l', 'l.id', '=', 'ld.labtest_id');
            $query->where('pl.patient_id', '=', $patientId);
            $query->orderBy('pl.id', 'DESC');*/

            $query = DB::table('patient as p')->select('pl.id as labtest_id','pl.unique_id as unique_id', 'pl.patient_id',
                    'p.pid', 'p.name', 'pl.labtest_date');
            $query->join('patient_labtest as pl', 'pl.patient_id', '=', 'p.patient_id');
            //$query->join('labtest_details as ld', 'ld.patient_labtest_id', '=', 'pl.id');
            //$query->join('labtest as l', 'l.id', '=', 'ld.labtest_id');
            $query->where('pl.patient_id', '=', $patientId);
            $query->orderBy('pl.id', 'DESC');

            $labTests = $query->get();
        }
        catch(QueryException $queryEx)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $exc);
        }

        return $labTests;
    }

    /**
     * Get lab test details for the given lab test id
     * @param $labTestId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getLabTestDetails($labTestId)
    {
        $labTestDetails = null;
        $patientDetails = null;
        $patientLabTests = null;

        try
        {
            /*$query = DB::table('patient as p')->select('p.patient_id', 'p.name', 'p.pid', 'p.telephone',
                'd.brand_name', 'pd.dosage', 'pd.no_of_days', 'pd.morning', 'pd.afternoon', 'pd.night');
            $query->join('patient_prescription as pp', 'pp.patient_id', '=', 'p.patient_id');
            $query->join('prescription_details as pd', 'pd.patient_prescription_id', '=', 'pp.id');
            $query->join('drugs as d', 'd.id', '=', 'pd.drug_id');
            $query->where('pp.id', '=', $prescriptionId);*/

            $patientQuery = DB::table('patient as p')->select('p.id', 'p.patient_id', 'p.name', 'p.pid', 'p.telephone', 'p.email');
            $patientQuery->join('patient_labtest as pl', 'pl.patient_id', '=', 'p.patient_id');
            $patientQuery->where('pl.id', '=', $labTestId);
            $patientDetails = $patientQuery->get();

            $doctorQuery = DB::table('doctor as d')->select('d.id', 'd.doctor_id', 'd.name', 'd.did', 'd.telephone', 'd.email');
            $doctorQuery->join('patient_labtest as pl', 'pl.doctor_id', '=', 'd.doctor_id');
            $doctorQuery->where('pl.id', '=', $labTestId);

            $doctorDetails = $doctorQuery->get();

            $query = DB::table('labtest_details as ld')->select('l.id', 'l.test_name', 'ld.brief_description', 'pl.labtest_date');
            $query->join('patient_labtest as pl', 'pl.id', '=', 'ld.patient_labtest_id');
            $query->join('labtest as l', 'l.id', '=', 'ld.labtest_id');
            $query->where('pl.id', '=', $labTestId);

            $labTestDetails = $query->get();

            $patientLabTests["PatientProfile"] = $patientDetails;
            $patientLabTests["DoctorProfile"] = $doctorDetails;
            $patientLabTests["PatientLabTestDetails"] = $labTestDetails;

            //dd($patientLabTests);

        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::LAB_DETAILS_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::LAB_DETAILS_ERROR, $exc);
        }

        return $patientLabTests;
    }

    /**
     * Save patient profile
     * @param $patientProfileVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientProfile(PatientProfileViewModel $patientProfileVM)
    {
        $status = true;
        $user = null;
        $patientId = null;
        $patient = null;

        try
        {
            $patientId = $patientProfileVM->getPatientId();
            //dd($patientId);

            if($patientId == 0)
            {
                $user = $this->registerNewPatient($patientProfileVM);
                $this->attachPatientRole($user);
                $patient = new Patient();
            }
            else
            {
                $patient = Patient::where('patient_id', '=', $patientId)->first();
                if(!is_null($patient))
                {
                    //$user = User::find($companyId);
                    $user = $this->registerNewPatient($patientProfileVM);
                }
            }

            $patient->name = $patientProfileVM->getName();
            $patient->address = $patientProfileVM->getAddress();
            $patient->city = $patientProfileVM->getCity();
            $patient->country = $patientProfileVM->getCountry();
            $patient->pid = 'PID'.crc32(uniqid(rand()));
            $patient->telephone = $patientProfileVM->getTelephone();
            $patient->email = $patientProfileVM->getEmail();
            $patient->patient_photo = $patientProfileVM->getPatientPhoto();
            $patient->dob = $patientProfileVM->getDob();
            $patient->age = $patientProfileVM->getPlaceOfBirth();
            $patient->nationality = $patientProfileVM->getNationality();
            $patient->gender = $patientProfileVM->getGender();
            $patient->married = $patientProfileVM->getMaritalStatus();

            $patient->created_by = $patientProfileVM->getCreatedBy();
            $patient->created_at = $patientProfileVM->getCreatedAt();
            $patient->updated_by = $patientProfileVM->getUpdatedBy();
            $patient->updated_at = $patientProfileVM->getUpdatedAt();

            $user->patient()->save($patient);
        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_PROFILE_SAVE_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_PROFILE_SAVE_ERROR, $exc);
        }

        return $status;
    }

    /**
     * Check if a patient is a new patient or follow up patient
     * @param $hospitalId, $doctorId, $patientId
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function checkIsNewPatient($hospitalId, $doctorId, $patientId)
    {
        $isNewPatient = true;

        try
        {
            $doctorUser = User::find($doctorId);
            $hospitalUser = User::find($hospitalId);
            $patientUser = User::find($patientId);

            if (!is_null($doctorUser) && !is_null($hospitalUser) && !is_null($patientUser))
            {
                $query = DB::table('patient_prescription as pp')->where('patient_id', '=', $patientId);
                $query->where('hospital_id', '=', $hospitalId);
                $query->where('doctor_id', '=', $doctorId);

                //dd($query->toSql());
                $count = $query->count();

                if($count > 0)
                {
                    $isNewPatient = false;
                }
            }
            else
            {
                $isNewPatient = false;
            }

        }
        catch(QueryException $queryEx)
        {
            //dd($queryEx);
            throw new HospitalException(null, ErrorEnum::NEW_PATIENT_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::NEW_PATIENT_ERROR, $exc);
        }

        return $isNewPatient;
    }

    private function registerNewPatient(PatientProfileViewModel $patientProfileVM)
    {
        $user = null;
        $patientId = $patientProfileVM->getPatientId();

        if($patientId == 0)
        {
            $user = new User();
        }
        else
        {
            $user = User::find($patientId);
        }

        $user->name = $patientProfileVM->getName();
        $user->email = $patientProfileVM->getEmail();
        $user->password = $patientProfileVM->getName();
        $user->delete_status = 1;
        $user->created_at = $patientProfileVM->getCreatedAt();
        $user->updated_at = $patientProfileVM->getUpdatedAt();

        $user->save();

        return $user;
    }

    private function attachPatientRole(User $user)
    {
        $role = Role::find(UserType::USERTYPE_PATIENT);

        if (!is_null($role))
        {
            $user->attachRole($role);
        }
    }

    /**
     * Save labtests for the patient
     * @param $patientLabTestVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientLabTests(PatientLabTestViewModel $patientLabTestVM)
    {
        $status = true;
        $patientLabTests = null;

        try
        {
            $doctorId = $patientLabTestVM->getDoctorId();
            $patientId = $patientLabTestVM->getPatientId();
            $hospitalId = $patientLabTestVM->getHospitalId();

            $doctorUser = User::find($doctorId);
            $hospitalUser = User::find($hospitalId);
            $patientUser = User::find($patientId);

            if (!is_null($doctorUser) && !is_null($hospitalUser) && !is_null($patientUser))
            {
                $patientLabTests = new PatientLabTests();
                $patientLabTests->hospital_id = $hospitalId;
                $patientLabTests->doctor_id = $doctorId;
                //$patientLabTests->unique_id = "LTID".time();

                $patientLabTests->unique_id = 'LTID'.crc32(uniqid(rand()));
                $patientLabTests->brief_description = $patientLabTestVM->getDescription();
                $patientLabTests->labtest_date = $patientLabTestVM->getLabTestDate();
                $patientLabTests->created_by = 'Admin';
                $patientLabTests->modified_by = 'Admin';
                $patientUser->labtests()->save($patientLabTests);
            }

            $this->saveLabTestDetails($patientLabTests, $patientLabTestVM);
        }
        catch(QueryException $queryEx)
        {
            $status = false;
            throw new HospitalException(null, ErrorEnum::LABTESTS_DETAILS_SAVE_ERROR, $queryEx);
        }
        catch(Exception $exc)
        {
            $status = false;
            throw new HospitalException(null, ErrorEnum::LABTESTS_DETAILS_SAVE_ERROR, $exc);
        }

        return $status;
    }

    private function saveLabTestDetails($patientLabTests, PatientLabTestViewModel $patientLabTestVM)
    {
        $labTests = $patientLabTestVM->getLabTestDetails();
        foreach($labTests as $labTest)
        {
            $labTestDetails = new LabTestDetails();

            $labTestObj = (object) $labTest;
            $labTestDetails->labtest_id = $labTestObj->labtestId;
            $labTestDetails->brief_description = $labTestObj->description;
            $labTestDetails->created_by = 'Admin';
            $labTestDetails->modified_by = 'Admin';

            $patientLabTests->labtestdetails()->save($labTestDetails);
        }
    }

    /**
     * Get the hospital id for the given pharmacy or lab id
     * @param $userId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */
    public function getHospitalId($userTypeId, $userId)
    {
        $hospitalId = null;
        $query = null;

        try
        {
            if(UserType::USERTYPE_PHARMACY == $userTypeId)
            {
                //$hospitalId = DB::table('hospital_pharmacy as hp')->where('hp.pharmacy_id', $userId)->select('hp.hospital_id')->get();
                $query = DB::table('hospital_pharmacy as hp')->where('hp.pharmacy_id', $userId)->select('hp.hospital_id');
            }
            elseif(UserType::USERTYPE_LAB == $userTypeId)
            {
                //$hospitalId = DB::table('hospital_lab as hl')->where('hl.lab_id', $userId)->select('hl.hospital_id')->get();
                $query = DB::table('hospital_lab as hl')->where('hl.lab_id', $userId)->select('hl.hospital_id');
            }

            //dd($query->toSql());
            $hospitalId = $query->get();

            //dd($hospitalId);

        }
        catch(QueryException $queryExc)
        {
            //dd($queryExc);
            throw new HospitalException(null, ErrorEnum::HOSPITAL_ID_ERROR, $queryExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            throw new HospitalException(null, ErrorEnum::HOSPITAL_ID_ERROR, $exc);
        }

        return $hospitalId;
    }

    public function test()
    {
        //dd('Inside test function in implementation');
    }
}