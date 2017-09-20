<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 20/09/2017
 * Time: 4:07 PM
 */

namespace App\Http\ViewModels;


class PatientLabReceiptViewModel
{
    private $patientId;
    private $hospitalId;
    private $bloodTests;
    private $motionTests;
    private $urineTests;
    private $scanTests;
    private $ultraSoundTests;

    private $createdBy;
    private $updatedBy;
    private $createdAt;
    private $updatedAt;

    public function __construct()
    {
        /*$this->bloodTests = array();
        $this->motionTests = array();
        $this->urineTests = array();*/
    }

    /**
     * @return mixed
     */
    public function getPatientId()
    {
        return $this->patientId;
    }

    /**
     * @param mixed $patientId
     */
    public function setPatientId($patientId)
    {
        $this->patientId = $patientId;
    }

    /**
     * @return mixed
     */
    public function getHospitalId()
    {
        return $this->hospitalId;
    }

    /**
     * @param mixed $hospitalId
     */
    public function setHospitalId($hospitalId)
    {
        $this->hospitalId = $hospitalId;
    }

    /**
     * @return mixed
     */
    public function getBloodTests()
    {
        return $this->bloodTests;
    }

    /**
     * @param mixed $bloodTests
     */
    public function setBloodTests($bloodTests)
    {
        //array_push($this->bloodTests, (object) $bloodTests);
        $this->bloodTests = $bloodTests;
    }

    /**
     * @return mixed
     */
    public function getMotionTests()
    {
        return $this->motionTests;
    }

    /**
     * @param mixed $motionTests
     */
    public function setMotionTests($motionTests)
    {
        //array_push($this->motionTests, (object) $motionTests);
        $this->motionTests = $motionTests;
    }

    /**
     * @return mixed
     */
    public function getUrineTests()
    {
        return $this->urineTests;
    }

    /**
     * @param mixed $urineTests
     */
    public function setUrineTests($urineTests)
    {
        //array_push($this->urineTests, (object) $urineTests);
        $this->urineTests = $urineTests;
    }

    /**
     * @return mixed
     */
    public function getScanTests()
    {
        return $this->scanTests;
    }

    /**
     * @param mixed $scanTests
     */
    public function setScanTests($scanTests)
    {
        $this->scanTests = $scanTests;
    }

    /**
     * @return mixed
     */
    public function getUltraSoundTests()
    {
        return $this->ultraSoundTests;
    }

    /**
     * @param mixed $ultraSoundTests
     */
    public function setUltraSoundTests($ultraSoundTests)
    {
        $this->ultraSoundTests = $ultraSoundTests;
    }

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return mixed
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param mixed $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }




}