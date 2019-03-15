<?php

namespace App\Controller;

/**
 * Data
 */
class Data {

	private $id;
	private $creationDate;
	private $deviceId;
	private $displayedName;
	private $hostId;
	private $actionMessage;
	private $isAction;
	private $buildingName;
	private $floorName;
	private $roomName;
	private $roomId;
	private $roomTypeName;
	private $roomTypeIcon;
	private $latitude;
	private $longitude;

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getCreationDate() {
		return $this->creationDate;
	}

	public function setCreationDate($creationDate) {
		$this->creationDate = $creationDate;
	}

	public function getDeviceId() {
		return $this->deviceId;
	}

	public function setDeviceId($deviceId) {
		$this->deviceId = $deviceId;
	}

	public function getDisplayedName() {
		return $this->displayedName;
	}

	public function setDisplayedName($displayedName) {
		$this->displayedName = $displayedName;
	}

	public function getHostId() {
		return $this->hostId;
	}

	public function setHostId($hostId) {
		$this->hostId = $hostId;
	}

	public function getActionMessage() {
		return $this->actionMessage;
	}

	public function setActionMessage($actionMessage) {
		$this->actionMessage = $actionMessage;
	}

	public function getIsAction() {
		return $this->isAction;
	}

	public function setIsAction($isAction) {
		$this->isAction = $isAction;
	}

	public function getBuildingName() {
		return $this->buildingName;
	}

	public function setBuildingName($buildingName) {
		$this->buildingName = $buildingName;
	}

	public function getFloorName() {
		return $this->floorName;
	}

	public function setFloorName($floorName) {
		$this->floorName = $floorName;
	}

	public function getRoomName() {
		return $this->roomName;
	}

	public function setRoomName($roomName) {
		$this->roomName = $roomName;
	}

	public function getRoomId() {
		return $this->roomId;
	}

	public function setRoomId($roomId) {
		$this->roomId = $roomId;
	}

	public function getRoomTypeName() {
		return $this->roomTypeName;
	}

	public function setRoomTypeName($roomTypeName) {
		$this->roomTypeName = $roomTypeName;
	}

	public function getRoomTypeIcon() {
		return $this->roomTypeIcon;
	}

	public function setRoomTypeIcon($roomTypeIcon) {
		$this->roomTypeIcon = $roomTypeIcon;
	}
	
	public function getLatitude() {
		return $this->latitude;
	}
	
	public function setLatitude($latitude) {
		$this->latitude = $latitude;
	}
	
	public function getLongitude() {
		return $this->longitude;
	}
	
	public function setLongitude($longitude) {
		$this->longitude = $longitude;
	}
}
