<?php

/**
 * Created by PhpStorm.
 * User: carlos
 * Date: 1/24/17
 * Time: 9:34 PM
 */
class StoreMapper extends Mapper
{

    public function register($name, $surname, $email, $password) {
        $conn = $this->db;
        $finalResponse = array();
        $response = array();
        $stmt = $conn->prepare("INSERT INTO user(name, surname, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $surname, $email, md5($password));
        $result = $stmt->execute();
        $stmt->close();
        $data = array();
        $errorCode = 500;

        if($result){
            $meta = array();
            $meta["status"] = "success";
            $meta["code"] = "200";
            $response["_meta"] = $meta;
            $errorCode = 200;
        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $meta["message"] = "No existe información";
            $response["_meta"] = $meta;
            $errorCode = 500;
        }

        $finalResponse["errorCode"]= $errorCode;
        $finalResponse["response"]=$response;

        return $finalResponse;
    }

    public function login($email, $password) {
        $conn = $this->db;
        $finalResponse = array();
        $response = array();
        $stmt = $conn->prepare("SELECT user_id, name, surname, email FROM user WHERE email = ? and password = ?");
        $stmt->bind_param("ss", $email, md5($password));
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $data = array();
        $errorCode = 500;

        if($result->num_rows >0){
            while ($dataQuery = $result->fetch_assoc()) {
                $tmp = array();
                $tmp["user_id"] = $dataQuery['user_id'];
                $tmp["name"] = $dataQuery['name'];
                $tmp["surname"] = $dataQuery['surname'];
                $tmp["email"] = $dataQuery['email'];
                //array_push($data, $tmp);
            }

            $response["data"] = $tmp;
            $errorCode = 200;
        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $meta["message"] = "Usuario y/o contraseña incorrectos";
            $response["_meta"] = $meta;
            $errorCode = 500;
        }

        $finalResponse["errorCode"]= $errorCode;
        $finalResponse["response"]=$response;

        return $finalResponse;
    }

/*

// add Brand
    public function addBrand($description, $image) {
        $response = array();
        $stmt = $this->conn->prepare("INSERT INTO brand(description, image) VALUES(?,?)");
        $stmt->bind_param("ss", $description, $image);
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            $meta = array();
            $meta["status"] = "success";
            $meta["code"] = "200";
            $response["_meta"] = $meta;
        } else {
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $response["_meta"] = $meta;
        }
        return $response;
    }


/*
    public function getAllYear() {
        $conn = $this->db;
        $finalResponse = array();
        $response = array();
        $stmt = $conn->prepare("SELECT DISTINCT year_sale FROM sale_master ORDER BY year_sale");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $data = array();
        $errorCode = 500;

        if($result->num_rows >0){
            while ($dataQuery = $result->fetch_assoc()) {
                $tmp = array();
                $tmp["year_sale"] = $dataQuery['year_sale'];
                array_push($data, $tmp);
            }

            $response["data"] = $data;
            $errorCode = 200;
        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $meta["message"] = "No existe información";
            $response["_meta"] = $meta;
            $errorCode = 500;
        }

        $finalResponse["errorCode"]= $errorCode;
        $finalResponse["response"]=$response;

        return $finalResponse;
    }

    // All Years
    public function getAllStore() {
        $conn = $this->db;
        $finalResponse = array();

        $stmt = $conn->prepare("SELECT DISTINCT store_id, store_description FROM sale_master ORDER BY store_description");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $data = array();
        $errorCode = 500;

        if($result->num_rows >0){

            while ($dataQuery = $result->fetch_assoc()) {
                $tmp = array();
                $tmp["store_id"] = $dataQuery['store_id'];
                $tmp["store_description"] = $dataQuery['store_description'];
                array_push($data, $tmp);
            }

            $response["data"] = $data;
            $errorCode = 200;

        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $meta["message"] = "No existe información";
            $response["_meta"] = $meta;
            $errorCode = 500;
        }

        $finalResponse["errorCode"]= $errorCode;
        $finalResponse["response"]=$response;

        return $finalResponse;
    }

    // Store sales by year
    public function getSalesStoreByYear($year) {
        $conn = $this->db;
        $finalResponse = array();
        $response = array();
        $stmt = $conn->prepare("SELECT DISTINCT store_id, store_description, year_sale, SUM(amount) as amount FROM sale_master WHERE year_sale = ? GROUP BY store_id, store_description, year_sale order by store_description");
        $stmt->bind_param("s", $year);
        if($stmt->execute()){
            $stmt->bind_result($store_id, $store_description, $year_sale, $amount);
            $stmt->store_result();
            if($stmt->num_rows>0){
                $data = array();
                while ($stmt->fetch()) {
                    $tmp = array();
                    $tmp["store_id"] = $store_id;
                    $tmp["store_description"] = $store_description;
                    $tmp["year_sale"] = $year_sale;
                    $tmp["amount"] = $amount;
                    array_push($data, $tmp);
                }
                $errorCode = 200;
                $response["data"] = $data;
            }else{
                $meta = array();
                $meta["status"] = "error";
                $meta["code"] = "101";
                $response["_meta"] = $meta;
                $errorCode = 500;
            }
        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $response["_meta"] = $meta;
            $errorCode = 500;
        }

        $finalResponse["errorCode"]= $errorCode;
        $finalResponse["response"]=$response;

        return $finalResponse;
    }

    // Branch sales by store and year
    public function getBranchSalesStore($store_id) {
        $conn = $this->db;
        $finalResponse = array();
        $response = array();
        $responseTotal = array();
        $stmtYear = $conn->prepare("SELECT DISTINCT year_sale FROM sale_master ORDER BY year_sale");
        $stmtYear->execute();
        $resultYear = $stmtYear->get_result();
        $stmtYear->close();
        $dataYear = array();
        $errorCode = 500;

        if($resultYear->num_rows >0){

            while ($dataQueryYear = $resultYear->fetch_assoc()) {
                $year = $dataQueryYear['year_sale'];
                $data["year"] = $year;

                $stmtStore = $conn->prepare("SELECT store_id, store_description, branch_id, branch_description, year_sale, SUM(amount) as amount FROM sale_master WHERE year_sale = ? AND store_id = ? GROUP BY branch_id, branch_description, year_sale order by year_sale");
                $stmtStore->bind_param("ss", $year, $store_id);

                if($stmtStore->execute()){
                    $stmtStore->bind_result($store_id, $store_description, $branch_id, $branch_description, $year_sale, $amount);
                    $stmtStore->store_result();

                    if($stmtStore->num_rows>0){
                        $dataStore = array();
                        while ($stmtStore->fetch()) {
                            $tmp = array();
                            $tmp["store_id"] = $store_id;
                            $tmp["store_description"] = $store_description;
                            $tmp["branch_id"] = $branch_id;
                            $tmp["branch_description"] = $branch_description;
                            $tmp["year_sale"] = $year_sale;
                            $tmp["amount"] = $amount;
                            array_push($dataStore, $tmp);
                            $data["branch"] = $dataStore;
                        }

                        array_push($responseTotal, $data);
                        $response["data"] = $responseTotal;

                        $errorCode = 200;
                    }else{
                        $meta = array();
                        $meta["status"] = "error";
                        $meta["code"] = "101";
                        $myResponse["_meta"] = $meta;
                        $errorCode = 500;
                    }
                }else{
                    $meta = array();
                    $meta["status"] = "error";
                    $meta["code"] = "100";
                    $myResponse["_meta"] = $meta;
                    $errorCode = 500;
                }


            }

        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $meta["message"] = "No existe información";
            $response["_meta"] = $meta;
            $errorCode = 500;
        }

        $finalResponse["errorCode"]= $errorCode;
        $finalResponse["response"]=$response;

        return $finalResponse;
    }


    // Store sales by year
    public function getSalesStore($id_store) {
        $conn = $this->db;
        $finalResponse = array();
        $response = array();
        $stmt = $conn->prepare("SELECT DISTINCT store_id, store_description, year_sale, SUM(amount) as amount FROM sale_master WHERE store_id = ? GROUP BY store_id, store_description, year_sale order by year_sale");
        $stmt->bind_param("s", $id_store);
        if($stmt->execute()){
            $stmt->bind_result($store_id, $store_description, $year_sale, $amount);
            $stmt->store_result();
            if($stmt->num_rows>0){
                $data = array();
                while ($stmt->fetch()) {
                    $tmp = array();
                    $tmp["store_id"] = $store_id;
                    $tmp["store_description"] = $store_description;
                    $tmp["year_sale"] = $year_sale;
                    $tmp["amount"] = $amount;
                    array_push($data, $tmp);
                }
                $errorCode = 200;
                $response["data"] = $data;
            }else{
                $meta = array();
                $meta["status"] = "error";
                $meta["code"] = "101";
                $response["_meta"] = $meta;
                $errorCode = 500;
            }
        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $response["_meta"] = $meta;
            $errorCode = 500;
        }
        $finalResponse["errorCode"]= $errorCode;
        $finalResponse["response"]=$response;

        return $finalResponse;
    }

    // Store sales by year
    public function getSalesByStoreAndYear() {

        $conn = $this->db;
        $finalResponse = array();
        $response = array();
        $responseTotal = array();
        $stmtYear = $conn->prepare("SELECT DISTINCT year_sale FROM sale_master ORDER BY year_sale");
        $stmtYear->execute();
        $resultYear = $stmtYear->get_result();
        $stmtYear->close();
        $dataYear = array();
        $errorCode = 500;

        if($resultYear->num_rows >0){

            while ($dataQueryYear = $resultYear->fetch_assoc()) {
                $year = $dataQueryYear['year_sale'];
                $data["year"] = $year;

                $stmtStore = $conn->prepare("SELECT DISTINCT store_id, store_description, year_sale, SUM(amount) as amount FROM sale_master WHERE year_sale = ?
                            GROUP BY store_id, store_description, year_sale order by store_description");
                $stmtStore->bind_param("s", $year);

                if($stmtStore->execute()){
                    $stmtStore->bind_result($store_id, $store_description, $year_sale, $amount);
                    $stmtStore->store_result();

                    if($stmtStore->num_rows>0){
                        $dataStore = array();
                        while ($stmtStore->fetch()) {
                            $tmp = array();
                            $tmp["store_id"] = $store_id;
                            $tmp["store_description"] = $store_description;
                            $tmp["year_sale"] = $year_sale;
                            $tmp["amount"] = $amount;
                            array_push($dataStore, $tmp);
                            $data["store"] = $dataStore;
                        }

                        array_push($responseTotal, $data);
                        $response["data"] = $responseTotal;

                        $errorCode = 200;
                    }else{
                        $meta = array();
                        $meta["status"] = "error";
                        $meta["code"] = "101";
                        $myResponse["_meta"] = $meta;
                        $errorCode = 500;
                    }
                }else{
                    $meta = array();
                    $meta["status"] = "error";
                    $meta["code"] = "100";
                    $myResponse["_meta"] = $meta;
                    $errorCode = 500;
                }


            }

        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $meta["message"] = "No existe información";
            $response["_meta"] = $meta;
            $errorCode = 500;
        }

        $finalResponse["errorCode"]= $errorCode;
        $finalResponse["response"]=$response;

        return $finalResponse;
        
    }

    public function getYearSaleByStore($storeId){
        $conn = $this->db;
        $finalResponse = array();
        $response = array();
        $stmt = $conn->prepare("SELECT store_id, year_sale, SUM(amount) FROM sale_master WHERE store_id = ? GROUP BY store_id, year_sale");
        $stmt->bind_param("s", $storeId);
        if($stmt->execute()){
            $stmt->bind_result($store_id, $year_sale, $amoun);
            $stmt->store_result();
            if($stmt->num_rows>0){
                $data = array();
                while ($stmt->fetch()) {
                    $tmp = array();
                    $tmp["store_id"] = $store_id;
                    $tmp["year_sale"] = $year_sale;
                    $tmp["amount"] = $amoun;
                    array_push($data, $tmp);
                }
                $errorCode = 200;
                $response["data"] = $data;
            }else{
                $meta = array();
                $meta["status"] = "error";
                $meta["code"] = "101";
                $response["_meta"] = $meta;
                $errorCode = 500;
            }
        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $response["_meta"] = $meta;
            $errorCode = 500;
        }
        $finalResponse["errorCode"]= $errorCode;
        $finalResponse["response"]=$response;

        return $finalResponse;
    }
    */
}