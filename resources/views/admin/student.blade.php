@extends('master')

@section('Title')
    Student Form
@endsection

@section('Style')

@endsection

@section('Content')
    <div class="container">
        <div class="card-box mb-30">
					<div class="pd-20">
						<h4 class="text-blue h4">Data Table with Export Buttons</h4>
					</div>
					<div class="pb-20">
						<table class="table hover multiple-select-row data-table-export nowrap">
							<thead>
								<tr>
									<th class="table-plus datatable-nosort">Name</th>
									<th>Age</th>
									<th>Office</th>
									<th>Address</th>
									<th>Start Date</th>
									<th>Salart</th>
								</tr>
							</thead>
							<tbody>
                            @foreach ($data ?? '' as $item)
								<tr>
                                <td>{{$item->course_name}}</td>
                                <td>{{$item->course_name}}</td>
                                <td>{{$item->course_name}}</td>
                                <td>{{$item->course_name}}</td>
                                <td>{{$item->course_name}}</td>
                                <td>{{$item->course_name}}</td>
							   </tr>
                            @endforeach
							</tbody>
						</table>
					</div>
				</div>
				<!-- Export Datatable End -->
			</div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script type="text/javascript">

        jQuery.ajax({
			    type: "GET",
			    url: '/api/courses',
			    beforeSend: function() {

			    },
			    success: function(data) {
			        console.log(data.courses);
			    },
				error:function(data){
					console.log(data);
				}
			});

    </script>
@endsection

@section('Script')

@endsection
