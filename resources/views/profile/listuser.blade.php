@extends('layouts.app')

@section('title', 'All User')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
          @foreach (['danger', 'warning', 'success', 'info'] as $key)
           @if(Session::has($key))
               <div class="alert alert-{{ $key }} alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  {{ Session::get($key) }}
                </div>
           @endif
          @endforeach

          @if(count($allUser) > 0)
          <h1>List of User</h1>
          <hr>
          <div class="table-responsive">
            <table class="table table-hover">
            	<thead>
            		<tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Date</th>
                    <th colspan="3">Action</th>
            		</tr>
            	</thead>
            	<tbody>
                <?php
                    $counter = 1;
                ?>
                @foreach($allUser as $aUser)
                <?php
                    $approve = array(
                          "Yes"=>"user/approved/$aUser->id?approved=1",
                          "No"=>"user/approved/$aUser->id?approved=0"
                    );
                ?>
              		<tr>
                    <th><?php echo $counter++; ?></th>
                    <td>{{ $aUser->name }}</td>
                    <td>{{ $aUser->email }}</td>
                    @if ($aUser->role == 9)
                      <td>Admin</td>
                    @elseif ($aUser->role == 8)
                      <td>Editor</td>
                    @elseif ($aUser->role == 7)
                      <td>Contributor</td>
                    @elseif ($aUser->role == 1)
                      <td>Subscribe</td>
                    @endif
                    </td>
                    <?php
                      //remove time and change the format
                      $date_string = $aUser->updated_at;
                      $date = explode(" ",$date_string);
                      $newDate = date("d-m-Y", strtotime($date[0]));
                     ?>
                    <td>{{ $newDate }}</td>
                    <td><a href="{{ action('HomeController@profileid', $aUser->id) }}">View</a></td>
                    <td><a href="{{ action('HomeController@deleteuser', $aUser->id) }}">Delete</a></td>
                    @if($aUser->email !== Auth::user()->email)
                      <td><a href="{{ action('HomeController@profileeditid', $aUser->id) }}">Edit</a></td>
                    @else
                      <td><a href="{{ action('HomeController@profileedit') }}">Edit</a></td>
                    @endif
                    <td style=text-align:center;>
                    	<form class='form-inline'>
                          <div class='form-group'>
                        	<select id='approved' name='approved' class='form-control' onChange='document.location = this.value'>
                        		<option value=''>Select approve</option>
                        		  @if($aUser->approved !== '')
                                  <?php
                                    foreach($approve as $x => $x_value) {
                                    $getnumber = substr("$x_value",-1);
                                      if($aUser->approved === $getnumber){
                                        echo'<option value=" '.$x_value.' "  selected="selected"> '.$x.' </option>';
                                      }
                                      else {
                                        echo '<option value="'.$x_value.'">'.$x.'</option>';
                                      }
                                    }
                                  ?>
                                @else
                                  <?php
                                  foreach($approve as $x => $x_value) {
                                    echo '<option value="'.$x_value.'">'.$x.'</option>';
                                  }
                                  ?>
                                @endif
                        	</select>
                          </div>
                        </form>
                    </td>
              		</tr>
                @endforeach
            	</tbody>
            </table>
          </div>
          @else
          <br><div class="alert alert-warning" role="alert">Empty Blog! Please click Add Blog to add blog.</div>
          @endif
        </div>
    </div>
</div>
@endsection
