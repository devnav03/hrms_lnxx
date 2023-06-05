<section>	
<div class="container">
<div style="width: 340px; margin: 0 auto;">
<center style="margin-top: 5px;">
<img src="/organization/logo/lnxx_logo_logo.svg" alt="logo" style="width:120px" />
</center>  
</div>
<h5 style="font-family: sans-serif; font-size: 16px; margin: 10px 0; margin-top: 40px;">Candidate Basic Info </h5>
<table class="table table-condensed" style="border: 1px solid rgb(243, 243, 243); border-top: 0px;">
        <tbody>
        <tr>
            <td>Candidate Name</td>
            <td style="border-right: 1px solid #f3f3f3;">{{ $OfferLetters->name }}</td>   
            <td>Candidate Position</td>
            <td>{{ $position->position_name }}</td>
        </tr>
        <tr> 
            <td>Candidate Email</td>
            <td style="border-right: 1px solid #f3f3f3;">{{ $OfferLetters->email }}</td>
            <td>Candidate Gender</td>
            <td>male </td>
        </tr>
        <tr> 
            <td>Candidate Salary</td>
            <td style="border-right: 1px solid #f3f3f3;">{{ $OfferLetters->salary }}</td>
            <td>Candidate Mobile No.</td>
            <td>{{ $OfferLetters->mobile }}</td>
        </tr>
        <tr> 
            <td>Manager Name</td>
            <td style="border-right: 1px solid #f3f3f3;">{{ $OfferLetters->manager_name }}</td>
            <td>HR Email</td>
            <td>{{ $SendHrRequest->hr_email }}</td>
        </tr>
        </tbody>
</table>


<!-- <h5 style="font-family: sans-serif; font-size: 16px; margin: 10px 0; margin-top: 40px;">Uploaded Document </h5> -->

<table class="table table-condensed">
           
                    <tbody>
                    	<tr style="background-color:#eeeeee!important;">
                            <td colspan="6" style="text-align: left;"><h4 style="margin: 0px;">Uploaded Document</h4></td>
                        </tr>
                        <tr>
                            <td><b>S. No</b></td>
                            <td><b>Document Type</b></td>
                            <td><b>Title</b></td>
                            <td><b>File</b></td>
                            <td><b>Uploaded By</b></td>
                            <td><b>Uploaded At</b></td>
                        </tr>

                         <?php  $i = 0; 

                        $LcMolDocs = DB::table('medical_reports')->where('candidate_id', $candidate_id)->select('title', 'file_name', 'created_by', 'created_at')->get();
                        if(!empty($LcMolDocs)){ 
                            foreach($LcMolDocs as $LcMolDoc){
                        $i++;        
                        ?>
                        <tr>
                                <td><?php echo $i; ?></td>    
                                <td>Medical Test Report</td>
                                <td><?php echo $LcMolDoc->title;?></td>
                                <td><a class="btn btn-primary btn-xs" href="../uploads/medical_test/<?php echo $LcMolDoc->file_name;?>" target="_blank">View</a></td>
                            <?php  
                            $user_name = DB::table('users')->where('id', $LcMolDoc->created_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <td> <?php echo date('d M, Y', strtotime($LcMolDoc->created_at)); ?> </td>
                        </tr>

                        <?php
                        }
                    }

                        $LcMolDocs = DB::table('visa_documents')->where('candidate_id', $candidate_id)->select('title', 'file_name', 'created_by', 'created_at')->get();
                        if(!empty($LcMolDocs)){ 
                            foreach($LcMolDocs as $LcMolDoc){
                        $i++;        
                        ?>

                        <tr>
                                <td><?php echo $i; ?></td>    
                                <td>eVisa Document</td>
                                <td><?php echo $LcMolDoc->title;?></td>
                                <td><a class="btn btn-primary btn-xs" href="../uploads/upload_lc_mol_doc/<?php echo $LcMolDoc->file_name;?>" target="_blank">View</a></td>

                            <?php  
                            $user_name = DB::table('users')->where('id', $LcMolDoc->created_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                        
                            <td> <?php echo date('d M, Y', strtotime($LcMolDoc->created_at)); ?> </td>
                            </tr>

                        <?php
                        }
                    }
                        $LcMolDocs = DB::table('signed_lc_mol_docs')->where('candidate_id', $candidate_id)->select('title', 'file_name', 'created_by', 'created_at')->get();
                        if(!empty($LcMolDocs)){ 
                            foreach($LcMolDocs as $LcMolDoc){
                        $i++;        
                        ?>
                        <tr>
                                <td><?php echo $i; ?></td>    
                                <td>LC/MOL Signed Copy</td>
                                <td><?php echo $LcMolDoc->title;?></td>
                                <td><a class="btn btn-primary btn-xs" href="../uploads/upload_lc_mol_doc/<?php echo $LcMolDoc->file_name;?>" target="_blank">View</a></td>


                            <?php  
                            if($LcMolDoc->created_by == 'candidate'){ ?>
                            <td>Candidate</td>
                            <?php } else {
                            $user_name = DB::table('users')->where('id', $LcMolDoc->created_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <?php } ?>
                            <td> <?php echo date('d M, Y', strtotime($LcMolDoc->created_at)); ?> </td>
                            </tr>
                        <?php
                        }
                    }
                        $LcMolDocs = DB::table('lc_mol_docs')->where('candidate_id', $candidate_id)->select('title', 'file_name', 'created_by', 'created_at')->get();
                        if(!empty($LcMolDocs)){ 
                            foreach($LcMolDocs as $LcMolDoc){
                        $i++;        
                        ?>
                        <tr>
                                <td><?php echo $i; ?></td>    
                                <td>LC/MOL Document</td>
                                <td><?php echo $LcMolDoc->title;?></td>
                                <td><a class="btn btn-primary btn-xs" href="../uploads/upload_lc_mol_doc/<?php echo $LcMolDoc->file_name;?>" target="_blank">View</a></td>
                            <?php  
                            $user_name = DB::table('users')->where('id', $LcMolDoc->created_by)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <td> <?php echo date('d M, Y', strtotime($LcMolDoc->created_at)); ?> </td>
                        </tr>

                       <?php
                        }
                        }
                        if(!empty($getRequiredDoc)){ 
                        ?>
                            <?php foreach($getRequiredDoc as $requiredDoc){
                            $i++;
                                ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                            <?php 
                            $doc = DB::table('document_masters')->where('id', $requiredDoc->document_id)->select('document_title')->first();
                            ?>     
                                <td><?php echo $doc->document_title; ?></td>
                                <td><?php echo $requiredDoc->document_title;?></td>
                                <td><a class="btn btn-primary btn-xs" href="../uploads/candidate-upload-required-doc/<?php echo $requiredDoc->document_file;?>" target="_blank">View</a></td>

                            <?php if($requiredDoc->created_by == 'HR'){ 
                            $user_name = DB::table('users')->where('id', $requiredDoc->organisation_id)->select('name')->first();
                            ?>
                            <td> <?php echo $user_name->name; ?> </td>
                            <?php } else {  ?>   
                            <td> Candidate </td>
                            <?php } ?>
                            <td> <?php echo date('d M, Y', strtotime($requiredDoc->doc_upload_date)); ?> </td>
                            </tr>
                           <?php } ?>
                     
                            <?php } ?> 
                     
                           <?php 
                            $results_data = DB::table('send_offer_letters_to_candidates')->where('candidate_id', $candidate_id)->select('organisation_id', 'document_title', 'document_file', 'created_at')->first();
                           if(!empty($results_data->organisation_id)) {
                            
                            $document_title = json_decode($results_data->document_title);
                            $document_file = json_decode($results_data->document_file);
                            foreach ($document_title as $key => $value) {
                            	$i++;
                            ?>
                            <tr>
                            <td><?php echo $i; ?></td>
                            <td>Offer Letter</td>
                            <td><?php echo $value; ?></td>
                            <td>
                            <?php
                            foreach ($document_file as $key1 => $value1) {
                                if($key1 == $key) {
                            ?>    
                            <a target="_blank" class="btn btn-primary btn-xs" href="../uploads/upload_offer_letter_document/<?php echo $value1; ?>">View</a>
                            <?php } } ?>
                        </td>

                          <?php  
                            $user_name = DB::table('users')->where('id', $results_data->organisation_id)->select('name')->first();
                            ?>
                           <td><?php echo $user_name->name; ?></td>
                           <td><?php echo date('d M, Y', strtotime($results_data->created_at)); ?></td>
                        </tr>
                       
                        <?php }  } ?>
                        
                        <?php 

                        $results_data = DB::table('send_hr_requests')->where('id', $candidate_id)->select('candidate_resume', 'manager_name', 'created_at')->first();

                           if(!empty($results_data->candidate_resume)) {
                            $i = $i+1;
                            ?>
                            <tr>
                            <td><?php echo $i; ?></td>
                            <td>Resume</td>
                            <td></td>
                            <td>
                            <a target="_blank" class="btn btn-primary btn-xs" href="<?php echo $results_data->candidate_resume;?>">View</a></td>
                           <td><?php echo $results_data->manager_name; ?></td>
                           <td><?php echo date('d M, Y', strtotime($results_data->created_at)); ?></td>
        
                            </tr>
                        <?php } ?>
                        
                    </tbody>
                    </table>



</div>
</section>





<style type="text/css">
	
.container {
    max-width: 1120px;
    margin: 0 auto;
    padding: 4px 20px;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    color: rgb(0, 23, 55);
}
.table td {
    font-size: 0.875rem;
}
.table th, .table td {
    padding: 1.25rem 0.9375rem;
    vertical-align: top;
    border-top: 1px solid rgb(243, 243, 243);
    font-family: sans-serif;
}
table a.btn.btn-primary.btn-xs {
    background: #135cbb;
    color: #fff;
    text-decoration: none;
    padding: 6px 7px;
    font-size: 12px;
}





</style>



























