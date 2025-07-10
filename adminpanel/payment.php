<?php include("./database/connection.php") ?>
<?php include("./partial/header.php") ?>


                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Transaction History</h1>
                        
</div>

<table class="table">
  <thead>
    <tr>
      <th scope="col">Transaction Id: </th>
      <th scope="col">User</th>
      <th scope="col">Service</th>
      <th scope="col">Payment Method</th>
      <th scope="col">Payment</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">355454544545</th>
      <td>Mark</td>
      <td>Hair Cut <br>Beard Styling</td>
      <td>Card</td>
      <td>$200</td>
    </tr>
    <tr>
      <th scope="row">355454544543</th>
      <td>Gulsher</td>
      <td>Beard Styling</td>
      <td>Card</td>
      <td>$150</td>
    </tr>
    <tr>
      <th scope="row">355454544544</th>
      <td>Ahmad</td>
      <td>Hair Cut</td>
      <td>Card</td>
      <td>$50</td>
    </tr>
  </tbody>
</table>
                </div>
                <!-- /.container-fluid -->


<?php include("./partial/footer.php");