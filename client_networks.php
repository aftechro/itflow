<?php 

//Paging
if(isset($_GET['p'])){
  $p = intval($_GET['p']);
  $record_from = (($p)-1)*$_SESSION['records_per_page'];
  $record_to = $_SESSION['records_per_page'];
}else{
  $record_from = 0;
  $record_to = $_SESSION['records_per_page'];
  $p = 1;
}
  
if(isset($_GET['q'])){
  $q = mysqli_real_escape_string($mysqli,$_GET['q']);
}else{
  $q = "";
}

if(!empty($_GET['sb'])){
  $sb = mysqli_real_escape_string($mysqli,$_GET['sb']);
}else{
  $sb = "network_name";
}

if(isset($_GET['o'])){
  if($_GET['o'] == 'ASC'){
    $o = "ASC";
    $disp = "DESC";
  }else{
    $o = "DESC";
    $disp = "ASC";
  }
}else{
  $o = "ASC";
  $disp = "DESC";
}

//Rebuild URL
$url_query_strings_sb = http_build_query(array_merge($_GET,array('sb' => $sb, 'o' => $o)));

$sql = mysqli_query($mysqli,"SELECT SQL_CALC_FOUND_ROWS * FROM networks 
  WHERE network_client_id = $client_id 
  AND (network_name LIKE '%$q%' OR network_vlan LIKE '%$q%' OR network LIKE '%$q%' OR network_gateway LIKE '%$q%' OR network_dhcp_range LIKE '%$q%') 
  ORDER BY $sb $o LIMIT $record_from, $record_to");

$num_rows = mysqli_fetch_row(mysqli_query($mysqli,"SELECT FOUND_ROWS()"));

?>

<div class="card card-dark">
  <div class="card-header py-2">
    <h3 class="card-title mt-2"><i class="fa fa-fw fa-network-wired"></i> Networks</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNetworkModal"><i class="fas fa-fw fa-plus"></i> New Network</button>
    </div>
  </div>
  <div class="card-body">
    <form autocomplete="off">
      <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
      <input type="hidden" name="tab" value="<?php echo $_GET['tab']; ?>">
      <div class="row">
        
        <div class="col-md-4">
          <div class="input-group mb-3 mb-md-0">
            <input type="search" class="form-control" name="q" value="<?php if(isset($q)){echo stripslashes($q);} ?>" placeholder="Search <?php echo ucwords($_GET['tab']); ?>">
            <div class="input-group-append">
              <button class="btn btn-dark"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>

        <div class="col-md-8">
          <div class="float-right">
            <a href="post.php?export_client_<?php echo $_GET['tab']; ?>_csv=<?php echo $client_id; ?>" class="btn btn-default"><i class="fa fa-fw fa-download"></i> Export</a>
          </div>
        </div>

      </div>
    </form>
    <hr>
    <div class="table-responsive">
      <table class="table table-striped table-borderless table-hover">
        <thead class="text-dark <?php if($num_rows[0] == 0){ echo "d-none"; } ?>">
          <tr>
            <th><a class="text-secondary" href="?<?php echo $url_query_strings_sb; ?>&sb=network_name&o=<?php echo $disp; ?>">Name</a></th>
            <th><a class="text-secondary" href="?<?php echo $url_query_strings_sb; ?>&sb=network_vlan&o=<?php echo $disp; ?>">vLAN</a></th>
            <th><a class="text-secondary" href="?<?php echo $url_query_strings_sb; ?>&sb=network&o=<?php echo $disp; ?>">Network</a></th>
            <th><a class="text-secondary" href="?<?php echo $url_query_strings_sb; ?>&sb=network_gateway&o=<?php echo $disp; ?>">Gateway</a></th>
            <th><a class="text-secondary" href="?<?php echo $url_query_strings_sb; ?>&sb=network_dhcp_range&o=<?php echo $disp; ?>">DHCP Range</a></th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
      
          while($row = mysqli_fetch_array($sql)){
            $network_id = $row['network_id'];
            $network_name = $row['network_name'];
            $network_vlan = $row['network_vlan'];
            if(empty($network_vlan)){
              $network_vlan_display = "-";
            }else{
              $network_vlan_display = $network_vlan;
            }
            $network = $row['network'];
            $network_gateway = $row['network_gateway'];
            $network_dhcp_range = $row['network_dhcp_range'];
            if(empty($network_dhcp_range)){
              $network_dhcp_range_display = "-";
            }else{
              $network_dhcp_range_display = $network_dhcp_range;
            }
            $network_created_at = $row['network_created_at'];
            $network_location_id = $row['network_location_id'];
      
          ?>
          <tr>
            <th>
              <i class="fa fa-fw fa-network-wired text-secondary"></i> 
              <a class="text-dark" href="#" data-toggle="modal" data-target="#editNetworkModal<?php echo $network_id; ?>"><?php echo $network_name; ?></a></th>
            <td><?php echo $network_vlan_display; ?></td>
            <td><?php echo $network; ?></td>
            <td><?php echo $network_gateway; ?></td>
            <td><?php echo $network_dhcp_range_display; ?></td>
            <td>
              <div class="dropdown dropleft text-center">
                <button class="btn btn-secondary btn-sm" type="button" data-toggle="dropdown">
                  <i class="fas fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editNetworkModal<?php echo $network_id; ?>">Edit</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item text-danger" href="post.php?delete_network=<?php echo $network_id; ?>">Delete</a>
                </div>
              </div>
            </td>
          </tr>

          <?php
          
          include("edit_network_modal.php");
          }
          
          ?>

        </tbody>
      </table>
    </div>
    <?php include("pagination.php"); ?>
  </div>
</div>

<?php include("add_network_modal.php"); ?>