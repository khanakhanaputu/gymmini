<?php


    function createMember($data, $conn) {
        $nama = $data["nama"];
        $noTelp = $data["noTelp"];
        $tglLahir = $data["tglLahir"];
        $jk = $data["jk"];
        $userId = $data["user_id"];

        $query = "INSERT INTO members(nama_members, notelp_members, jk_members, tgl_lahir_members, user_id) VALUES(?,?,?,?,?)";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $nama, $noTelp, $jk, $tglLahir, $userId);
        $result = $stmt->execute();

        if($result) {
            return true;
        } else {
            return false;
        }
    }

    function getAllMember($conn)  {
        $query = "SELECT * FROM members WHERE status_members = 1";
        $result = mysqli_query($conn, $query);

        $data_member = [];
        if($result && mysqli_num_rows($result) > 0){
            while($rows = mysqli_fetch_assoc($result)){
                $data_member[] = $rows;
            }
        }

        return $data_member;
    
    }

    function getMemberById($id, $conn) {
        $query = "SELECT * FROM members WHERE members_id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $dataMemberById = null;
        if($result && mysqli_num_rows($result) > 0){
            $dataMemberById = mysqli_fetch_assoc($result);
        }

        return $dataMemberById;
    }

    function patchMember($data, $conn) {

        $memberId = $data["member_id"];
        $nama = $data["nama"];
        $noTelp = $data["noTelp"];
        $tglLahir = $data["tglLahir"];
        $jk = $data["jk"];


        $query = "UPDATE members SET nama_members = ?, 
                                     notelp_members = ?,
                                     jk_members = ?,
                                     tgl_lahir_members = ? WHERE members_id = ? AND status_members = 1";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $nama, $noTelp, $jk, $tglLahir, $memberId);
        $isExecuted = $stmt->execute();
        

        if($isExecuted){
            return true;
        }

        return false;
    } 
    
    function deleteMember($id, $conn) {
        $query = "UPDATE members SET status_members = 0 WHERE members_id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $isExecuted = $stmt->execute();
        

        if($isExecuted){
            return true;
        }

        return false;
    } 



?>