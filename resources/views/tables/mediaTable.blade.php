@extends('layouts.app')

<!-- Scripts -->
@vite(['resources/css/table.css' , 'resources/js/table.js'])
@section('content')
<body>
<?php $permis = Auth::user()->role ;
      $dpm = Auth::user()->dpm;
?>
    <div class="container">
        <div class="text-center mb-4"><h2>ทะเบียน สื่อการตลาด</h2></div>
        <div class="d-flex">
            <div class="flex-grow-1"><input type="text" id="searchInput" class="form-control mb-2" placeholder="Search..."></div>
            <div class="p-1 ms-2 export"><a class="a-tag" href="/export/table/wiTable"><i class="bi bi-file-earmark-arrow-down"></i></a></div>
        </div>
        <!-- Table -->
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-all-tab" data-bs-toggle="tab" data-bs-target="#nav-all" type="button" role="tab" aria-controls="nav-all" aria-selected="true">All</button>
                @foreach ($type as $item)
                    <button class="nav-link" id="nav-{{ $item->subtype }}-tab" data-bs-toggle="tab" data-bs-target="#nav-{{ $item->subtype }}" type="button" role="tab" aria-controls="nav-{{ $item->subtype }}" aria-selected="false">{{ $item->subtype }}</button>
                @endforeach
                <button class="btn btn-success ms-auto" id="addSubtype"><i class="bi bi-plus-circle fs-5"></i></button>
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane table-responsive fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab" tabindex="0">
                <table class="table table-hover ">

                    <!-- Table Header -->
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">เลขที่หนังสือ</th>
                            <th scope="col">ผู้สร้าง</th>
                            <th scope="col">เรื่อง</th>
                            <th scope="col">วันที่สร้าง</th>
                            <th scope="col">ผู้บันทึก</th>
                            <th scope="col">สถานะ</th>
                            <th scope="col">แก้ไข</th>
                            <th scope="col">Download</th>
                            <th scope="col">แนบไฟล์</th>
                            @can('staff')
                            <th scope="col">Share</th>
                        @endcan
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="table-group-divider" id="tableBody">
                        <!-- Table rows will be dynamically added here -->
                        <?php  $counter = 1 ?>
                        @foreach ($gendoc as $row)
                            <tr>
                                <td>{{$counter}}</td>
                                <td>{{ $row->book_num}}</td>
                                <td>{{ $row->bcreater}}</td>
                                <td class="truncate">{{ $row->title}}</td>
                                <td>{{ $row->created_date}}</td>
                                <td>
                                    @php
                                        $submitUser = $user->firstWhere('id', $row->submit_by);
                                        echo $submitUser ? $submitUser->name : 'Unknown';
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $app = json_decode($row->app);
                                        $ins = json_decode($row->ins);
                                        $appName = $user->firstWhere('id', $app->appId ?? '') ?? '-';
                                        $insName = $user->firstWhere('id', $ins->appId ?? '') ?? '-';
                                        $note = $app->note ?? '-';
                                        $insnote = $ins->note ?? '-';
                                    @endphp
                                    @if ($row->stat === 'ยังไม่ได้ตรวจสอบ')
                                        <button class="btn btn-info" name="{{$row->stat}}" docType="{{$row->type}}" id="status" value="{{$row->id}}">{{$row->stat}}</button>
                                    @elseif ($row->stat === 'ผ่านการอนุมัติ')
                                        <button class="btn btn-success"
                                                name="{{$row->stat}}"
                                                docType="{{$row->type}}"
                                                id="passbtn"
                                                note="{{$note}}"
                                                insnote="{{$insnote}}"
                                                appName="{{$appName->name}}"
                                                insName="{{$insName->name}}"
                                                value="{{$row->id}}">{{$row->stat}}</button>
                                    @elseif ($row->stat === 'ไม่ผ่านการตรวจสอบ' || $row->stat === 'ไม่ผ่านการอนุมัติ')

                                        <button class="btn btn-danger"
                                                name="{{$row->stat}}"
                                                id="notpass"
                                                note="{{$note}}"
                                                insnote="{{$insnote}}"
                                                appName="{{$appName->name}}"
                                                insName="{{$insName->name}}"
                                                docType="{{$row->type}}"
                                                value="{{$row->id}}">{{$row->stat}}</button>
                                    @else
                                        <button class="btn btn-secondary" name="{{$row->stat}}" docType="{{$row->type}}" value="{{$row->id}}">{{$row->stat}}</button>
                                    @endif
                                </td>


                                @can('create')
                                    <td>
                                        <a href="{{url('/form/editmedia/'.$row->id)}}"><button type="button" class="btn btn-warning">Edit</button></a>
                                    </td>
                                @else
                                    <td></td>
                                @endcan


                                @can('download')
                                    <td>
                                        <a href="{{url('/form/downloadmedia/download/'.$row->id)}}" target="_blank"><button type="button" class="btn btn-primary">Download</button></a>
                                    </td>
                                @else
                                    <td>
                                        <a href="{{url('/form/downloadmedia/download/'.$row->id)}}" target="_blank"><button type="button" class="btn btn-primary">View</button></a>
                                    </td>
                                @endcan

                                <td class="text-center">
                                    @if ($row->files != null)
                                        @php
                                            $fileList = $row->files;
                                        @endphp
                                        @foreach (json_decode($fileList) as $file)
                                            <button type="button" data-file-path="{{ asset('files/' . $file) }}" class="btn btn-secondary viewFilebtn mb-1"  value="{{$file}}" fileId="{{$row->id}}">{{$file}}</button>
                                        @endforeach
                                    @else

                                    @endif
                                    <button type="button" class="btn btn-info uploadBtn" value="{{$row->id}}" fileType="policy">upload</button>
                                </td>
                                @can('staff')
                                <td><button class="btn btn-success" id="shareBtn" value="{{ $row->share}}" bookid="{{ $row->id}}" fileType="media">
                                    @php
                                        $share = $row->shares;
                                        $teamArr = json_decode($share);
                                        if (is_array($teamArr)) {
                                            foreach ($teamArr as $memb) {
                                                echo ($memb ? (App\Models\department::find($memb))->name : '') . " / ";
                                            }
                                        } else {
                                            echo "share";
                                        }
                                        $permis = Auth::user()->role ;
                                        $dpm = Auth::user()->dpm;
                                    @endphp
                                    </button>
                                </td>
                            @endcan
                            </tr>
                            <?php $counter++ ?>
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination Links -->
                <div class="d-flex justify-content-end pagination">
                    {{ $gendoc->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>

            @foreach ($type as $item)

                <div class="tab-pane fade table-responsive" id="nav-{{ $item->subtype }}" role="tabpanel" aria-labelledby="nav-{{ $item->subtype }}-tab" tabindex="0">
                    <!-- Contract imported table -->
                    <table class="table table-hover ">

                        <!-- Table Header -->
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">เลขที่หนังสือ</th>
                                <th scope="col">ผู้สร้าง</th>
                                <th scope="col">เรื่อง</th>
                                <th scope="col">วันที่สร้าง</th>
                                <th scope="col">ผู้บันทึก</th>
                                <th scope="col">สถานะ</th>
                                <th scope="col">แก้ไข</th>
                                <th scope="col">Download</th>
                                <th scope="col">แนบไฟล์</th>
                            </tr>
                        </thead>

                        <!-- Table Body -->
                        <tbody class="table-group-divider" id="tableBody">
                            <!-- Table rows will be dynamically added here -->
                            <?php  $counter = 1 ?>
                            @foreach ($gendoc as $row)
                                @if (strpos($row->type, $item->subtype) !== false)
                                    <tr>
                                        <td>{{$counter}}</td>
                                        <td>{{ $row->book_num}}</td>
                                        <td>{{ $row->bcreater}}</td>
                                        <td class="truncate">{{ $row->title}}</td>
                                        <td>{{ $row->created_date}}</td>
                                        <td>
                                            @php
                                                $submitUser = $user->firstWhere('id', $row->submit_by);
                                                echo $submitUser ? $submitUser->name : 'Unknown';
                                            @endphp
                                        </td>
                                        <td>
                                            @php
                                                $app = json_decode($row->app);
                                                $ins = json_decode($row->ins);
                                                $appName = $user->firstWhere('id', $app->appId ?? '') ?? '-';
                                                $insName = $user->firstWhere('id', $ins->appId ?? '') ?? '-';
                                                $note = $app->note ?? '-';
                                                $insnote = $ins->note ?? '-';
                                            @endphp
                                            @if ($row->stat === 'ยังไม่ได้ตรวจสอบ')
                                                <button class="btn btn-info" name="{{$row->stat}}" docType="{{$row->type}}" id="status" value="{{$row->id}}">{{$row->stat}}</button>
                                            @elseif ($row->stat === 'ผ่านการอนุมัติ')
                                                <button class="btn btn-success"
                                                        name="{{$row->stat}}"
                                                        docType="{{$row->type}}"
                                                        id="passbtn"
                                                        note="{{$note}}"
                                                        insnote="{{$insnote}}"
                                                        appName="{{$appName->name}}"
                                                        insName="{{$insName->name}}"
                                                        value="{{$row->id}}">{{$row->stat}}</button>
                                            @elseif ($row->stat === 'ไม่ผ่านการตรวจสอบ' || $row->stat === 'ไม่ผ่านการอนุมัติ')

                                                <button class="btn btn-danger"
                                                        name="{{$row->stat}}"
                                                        id="notpass"
                                                        note="{{$note}}"
                                                        insnote="{{$insnote}}"
                                                        appName="{{$appName->name}}"
                                                        insName="{{$insName->name}}"
                                                        docType="{{$row->type}}"
                                                        value="{{$row->id}}">{{$row->stat}}</button>
                                            @else
                                                <button class="btn btn-secondary" name="{{$row->stat}}" docType="{{$row->type}}" value="{{$row->id}}">{{$row->stat}}</button>
                                            @endif
                                        </td>


                                        @can('create')
                                            <td>
                                                <a href="{{url('/form/editmedia/'.$row->id)}}"><button type="button" class="btn btn-warning">Edit</button></a>
                                            </td>
                                        @else
                                            <td></td>
                                        @endcan


                                        @can('download')
                                            <td>
                                                <a href="{{url('/form/downloadmedia/download/'.$row->id)}}" target="_blank"><button type="button" class="btn btn-primary">Download</button></a>
                                            </td>
                                        @else
                                            <td>
                                                <a href="{{url('/form/downloadmedia/download/'.$row->id)}}" target="_blank"><button type="button" class="btn btn-primary">View</button></a>
                                            </td>
                                        @endcan

                                        <td class="text-center">
                                            @if ($row->files != null)
                                                @php
                                                    $fileList = $row->files;
                                                @endphp
                                                @foreach (json_decode($fileList) as $file)
                                                    <button type="button" data-file-path="{{ asset('files/' . $file) }}" class="btn btn-secondary viewFilebtn mb-1"  value="{{$file}}" fileId="{{$row->id}}">{{$file}}</button>
                                                @endforeach
                                            @else

                                            @endif
                                            <button type="button" class="btn btn-info uploadBtn" value="{{$row->id}}" fileType="policy">upload</button>
                                        </td>
                                    </tr>
                                @endif
                                <?php $counter++ ?>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                </div>

            @endforeach
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const statbtns = document.querySelectorAll('#status');
        statbtns.forEach((ckbtn) => {
            let status = ckbtn.getAttribute('name');
            let docid = ckbtn.value;
            let type = ckbtn.getAttribute('docType');
            ckbtn.addEventListener('click', function () {
                Swal.fire({
                    title: 'Verification',
                    html: `
                        <select class="form-select mb-2" id="ins" >
                            <option value="" selected disabled>กรุณาเลือกผู้ตรวจสอบ</option>
                            @foreach ($inspectors as $ins)
                                <option value="{{$ins->id}}">{{$ins->name}}</option>
                            @endforeach
                        </select>
                        <select class="form-select mb-2" id="appr" >
                            <option value="" selected disabled>กรุณาเลือกผู้อนุมัติ</option>
                            @foreach ($approvers as $appr)
                                <option value="{{$appr->id}}">{{$appr->name}}</option>
                            @endforeach
                        </select>
                        `,
                    showCancelButton: true,
                    preConfirm: () => {
                        const insValue = document.getElementById('ins').value;
                        const appValue = document.getElementById('appr').value;
                        if (!insValue || !appValue) {
                            return Promise.reject('Please select both inspector and approver.');
                        }

                        return [insValue, appValue];
                    }
                }).then((result) => {
                    if (result.isConfirmed) {

                        fetch('/table/form/verify', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Replace with the actual CSRF token
                            },
                            body: JSON.stringify({
                                ins: result.value[0],
                                app: result.value[1],
                                docId: docid,
                                status: status,
                                type: type,
                            }),
                        })
                        .then((response) => response.json())
                        .then((data) => {
                            // Handle the response if needed
                            console.log(data);
                            // You can also reload the page to see the changes,
                            window.location.reload();
                        })
                        .catch((error) => {
                            // Handle errors if any
                            Swal.fire('Error!', error.message, 'error');
                        });
                    }
                }).catch((error) => {
                    Swal.fire('Error!', error, 'error'); // Display error to user
                });
            });
        });


        const notpbtns = document.querySelectorAll('#notpass');
        notpbtns.forEach((ckbtn) => {
            let status = ckbtn.getAttribute('name');
            let docid = ckbtn.value;
            let type = ckbtn.getAttribute('docType');
            let appName = ckbtn.getAttribute('appName');
            let appnote = ckbtn.getAttribute('note');
            let insName = ckbtn.getAttribute('insName');
            let insnote = ckbtn.getAttribute('insnote');
            ckbtn.addEventListener('click', function () {
                Swal.fire({
                    title: status,
                    html: `ผู้ตรวจสอบ: ${insName}<br>Note: ${insnote}<br>
                            ผู้อนุมัติ: ${appName}<br>Note: ${appnote}
                            `,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'verify'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Verification',
                            html: `
                                <select class="form-select mb-2" id="ins" >
                                    <option value="" selected disabled>กรุณาเลือกผู้ตรวจสอบ</option>
                                    @foreach ($inspectors as $ins)
                                        <option value="{{$ins->id}}">{{$ins->name}}</option>
                                    @endforeach
                                </select>
                                <select class="form-select mb-2" id="appr" >
                                    <option value="" selected disabled>กรุณาเลือกผู้อนุมัติ</option>
                                    @foreach ($approvers as $appr)
                                        <option value="{{$appr->id}}">{{$appr->name}}</option>
                                    @endforeach
                                </select>
                                `,
                            showCancelButton: true,
                            preConfirm: () => {
                                const insValue = document.getElementById('ins').value;
                                const appValue = document.getElementById('appr').value;
                                if (!insValue || !appValue) {
                                    return Promise.reject('Please select both inspector and approver.');
                                }

                                return [insValue, appValue];
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                console.log(result);
                                fetch('/table/form/verify', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Replace with the actual CSRF token
                                    },
                                    body: JSON.stringify({
                                        ins: result.value[0],
                                        app: result.value[1],
                                        docId: docid,
                                        status: status,
                                        type: type,
                                    }),
                                })
                                .then((response) => response.json())
                                .then((data) => {
                                    // Handle the response if needed
                                    console.log(data);
                                    // You can also reload the page to see the changes,
                                    window.location.reload();
                                })
                                .catch((error) => {
                                    // Handle errors if any
                                    Swal.fire('Error!', error.message, 'error');
                                });
                            }
                        }).catch((error) => {
                            Swal.fire('Error!', error, 'error'); // Display error to user
                        });
                    }
                })
            });
        });


        const pbtns = document.querySelectorAll('#passbtn');
        pbtns.forEach((ckbtn) => {
            let status = ckbtn.getAttribute('name');
            let docid = ckbtn.value;
            let type = ckbtn.getAttribute('docType');
            let appName = ckbtn.getAttribute('appName');
            let appnote = ckbtn.getAttribute('note');
            let insName = ckbtn.getAttribute('insName');
            let insnote = ckbtn.getAttribute('insnote');
            ckbtn.addEventListener('click', function () {
                Swal.fire({
                    title: status,
                    html: `ผู้ตรวจสอบ: ${insName}<br>Note: ${insnote}<br>
                            ผู้อนุมัติ: ${appName}<br>Note: ${appnote}
                            `,
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    showConfirmButton: true,
                    });
            });
        });

        const pdfButtons = document.querySelectorAll('.viewFilebtn');
        pdfButtons.forEach((pdfbtn) => {
            const fileNameValue = pdfbtn.value;
            const formId = pdfbtn.getAttribute('fileId');
            const fileType = document.querySelector('.uploadBtn').getAttribute('fileType');
            pdfbtn.addEventListener('click', function () {
                const pdfUrl = this.getAttribute('data-file-path');
                Swal.fire({
                    showConfirmButton: false,
                    width: '70%',
                    html: '<div style="height: 600px;">' +
                        '<iframe src="' + pdfUrl + '" style="width: 100%; height: 100%;" frameborder="0"></iframe>' +
                        '</div>',
                    showDenyButton: true,
                    denyButtonText: 'Delete',
                }).then((result) => {
                    if (result.isDenied) {
                        console.log(result + fileNameValue + formId);
                        fetch('/table/deleteFile', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Replace with the actual CSRF token
                            },
                            body: JSON.stringify({
                                fileName: fileNameValue,
                                id: formId,
                                type: fileType,
                            }),
                        })
                        .then((response) => response.json())
                        .then((data) => {
                            // Handle the response if needed
                            console.log(data);
                            // You can also reload the page to see the changes, if required
                            window.location.reload();
                        })
                        .catch((error) => {
                            // Handle errors if any
                            Swal.fire('Error!', 'An error occurred while saving the data.', 'error');
                        });
                    }
                });
            });
        });

        const checkbtn = document.querySelectorAll('.uploadBtn');
        let statusValue;
        checkbtn.forEach((ckbtn) => {
            const type = ckbtn.getAttribute('fileType');
            ckbtn.addEventListener('click', function () {
                Swal.fire({
                    title: 'Select file',
                    input: 'file',
                    inputAttributes: {
                        'accept': 'pdf/*',
                        'aria-label': 'Upload your profile picture'
                    }
                }).then((result) => {
                    const file = result.value; // Get the selected file from the result object
                    statusValue = ckbtn.value;
                    if (file) {
                        saveData(file,type);
                    }
                });
            });
        });

        function saveData(file, type) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('type', type);
            formData.append('valueid', statusValue);

            // Send data to Laravel controller using fetch API
            fetch('/table/uploadFile', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Replace with the actual CSRF token
                },
                body: formData,
            })
            .then((response) => response.json())
            .then((data) => {
                // Handle the response if needed
                console.log(data);
                // You can also reload the page to see the changes, if required
                window.location.reload();
            })
            .catch((error) => {
                // Handle errors if any
                Swal.fire('Error!', 'An error occurred while saving the data.', 'error');
            });
        };

        const addSubtype = document.querySelector('#addSubtype');
        addSubtype.addEventListener('click',async function () {
            const { value: email } = await Swal.fire({
                title: 'เพิ่มหมวดหมู่',
                input: 'text',
                inputPlaceholder: 'Enter your type',
                confirmButtonText: 'บันทึก',
            });

            if (email) {
                fetch('/saveSubtype', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Replace with the actual CSRF token
                    },
                    body: JSON.stringify({
                        type: "media",
                        subtype: email,
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    // Handle the response if needed
                    console.log(data);
                    // You can also reload the page to see the changes, if required
                    window.location.reload();
                })
                .catch((error) => {
                    // Handle errors if any
                    Swal.fire('Error!', 'An error occurred while saving the data.', 'error');
                });
            };
        });

        const ptbtns = document.querySelectorAll('#shareBtn');
        ptbtns.forEach((ckbtn) => {
            const bookid = ckbtn.getAttribute('bookid');
            const type = ckbtn.getAttribute('fileType');
            const team = ckbtn.value;
            ckbtn.addEventListener('click', function () {
                Swal.fire({
                    title: 'เพิ่มฝ่ายที่เข้าถึงเอกสาร',
                    html: `
                        <select class="form-select mb-2" id="usrt" >
                            <option value="" selected disabled>กรุณาเลือกฝ่ายที่สามารถเข้าถึงเอกสารนี้ได้</option>
                            @foreach ($dpms as $dpm)
                                <option value="{{$dpm->id}}">{{$dpm->name}}</option>
                            @endforeach
                        </select>
                        `,
                    showCancelButton: true,
                    showDenyButton: true,
                    denyButtonText: 'ล้างรายชื่อทั้งหมด',
                    confirmButtonText: 'บันทึก',
                    cancelButtonText: 'ยกเลิก',
                    preConfirm: () => {
                        const usrtValue = document.getElementById('usrt').value;
                        if (!usrtValue) {
                            return Promise.reject('โปรดเลือกฝ่าย');
                        }

                        return [usrtValue];
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log(result);
                        fetch('/table/form/addShare', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Replace with the actual CSRF token
                            },
                            body: JSON.stringify({
                                memb: result.value[0],
                                bid: bookid,
                                oldT: team,
                                type: type,
                            }),
                        })
                        .then((response) => response.json())
                        .then((data) => {
                            // Handle the response if needed
                            console.log(data);
                            // You can also reload the page to see the changes,
                            window.location.reload();
                        })
                        .catch((error) => {
                            // Handle errors if any
                            Swal.fire('Error!', error.message, 'error');
                        });
                    } else if (result.isDenied) {
                        console.log(result);
                        fetch('/table/form/clearShare', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Replace with the actual CSRF token
                            },
                            body: JSON.stringify({
                                bid: bookid,
                                type: type,
                            }),
                        })
                        .then((response) => response.json())
                        .then((data) => {
                            // Handle the response if needed
                            console.log("res= " + data);
                            // You can also reload the page to see the changes,
                            window.location.reload();
                        })
                        .catch((error) => {
                            // Handle errors if any
                            Swal.fire('Error!', error.message, 'error');
                        });
                    }
                }).catch((error) => {
                    Swal.fire('Error!', error, 'error'); // Display error to user
                });
            });
        });
    </script>
</body>
@endsection
