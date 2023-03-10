<?php

namespace App\Http\Controllers;

use App\Models\Mikrotik;
use App\Repositories\HotspotRepository;
use RouterOS\Exceptions\BadCredentialsException;
use RouterOS\Exceptions\ClientException;
use RouterOS\Exceptions\ConfigException;
use RouterOS\Exceptions\ConnectException;
use RouterOS\Exceptions\QueryException;
use Yajra\DataTables\Facades\DataTables;

class HotspotController extends Controller
{
    private HotspotRepository $hotspotRepository;

    public function __construct(
        HotspotRepository $hotspotRepository
    )
    {
        $this->hotspotRepository = $hotspotRepository;
    }


    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function index(Mikrotik $mikrotik)
    {
        $data = $this->hotspotRepository->getHotspotActive();

        if (request()->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('user', function ($data) {
                    return '<span class="badge badge-info" data-toggle="tooltip" data-original-title="Username">' . $data['user'] . '</span>';
                })->editColumn('server', function ($data) {
                    return '<span class="badge badge-primary" data-toggle="tooltip" data-original-title="Server">' . $data['server'] . '</span>';
                })
                ->addColumn('session_time_left', function ($row) {
                    return $row->session_time_left ?? 'N/A';
                })->addColumn('action', function ($data) {
                    return view('mikrotik.hotspot.partials._action')->with('data', $data);
                })->rawColumns(['user', 'action', 'server'])
                ->make(true);
        }
        $pools = $this->hotspotRepository->getIpPool();
        $simplequeue = $this->hotspotRepository->getSimpleQueue();
        return view('mikrotik.hotspot.index', [
            'mikrotik' => $mikrotik,
            'pools' => $pools,
            'simplequeue' => $simplequeue
        ]);
    }


    public function createUserProfile()
    {
        $namewithspace = request()->pname;
        $name = preg_replace('/\s+/', '-', $namewithspace);
        $sharedusers = request()->pshared;
        $ratelimit = request()->plimit;
        $expmode = request()->pexpmode;
        $validity = request()->pvalidity;
        $graceperiod = request()->graceperiod;
        $getprice = request()->pprice;
        $getsprice = request()->psellingprice;
        $addrpool = request()->ppool;

        if ($getprice == "") {
            $price = "0";
        } else {
            $price = $getprice;
        }
        if ($getsprice == "") {
            $sprice = "0";
        } else {
            $sprice = $getsprice;
        }

        $getlock = request()->plock;
        if ($getlock == "Enable") {
            $lock = '; [:local mac $"mac-address"; /ip hotspot user set mac-address=$mac [find where name=$user]]';
        } else {
            $lock = "";
        }

        $randstarttime = "0" . rand(1, 5) . ":" . rand(10, 59) . ":" . rand(10, 59);
        $randinterval = "00:02:" . rand(10, 59);

        $parent = request()->pqueue;

        $record = '; :local mac $"mac-address"; :local time [/system clock get time ]; /system script add name="$date-|-$time-|-$user-|-' . $price . '-|-$address-|-$mac-|-' . $validity . '-|-' . $name . '-|-$comment" owner="$month$year" source=$date comment=mikhmon';

        $onlogin = ':put (",' . $expmode . ',' . $price . ',' . $validity . ',' . $sprice . ',' . $getlock . ',"); {:local date [ /system clock get date ];:local year [ :pick $date 7 11 ];:local month [ :pick $date 0 3 ];:local comment [ /ip hotspot user get [/ip hotspot user find where name="$user"] comment]; :local ucode [:pic $comment 0 2]; :if ($ucode = "vc" or $ucode = "up" or $comment = "") do={ /sys sch add name="$user" disable=no start-date=$date interval="' . $validity . '"; :delay 2s; :local exp [ /sys sch get [ /sys sch find where name="$user" ] next-run]; :local getxp [len $exp]; :if ($getxp = 15) do={ :local d [:pic $exp 0 6]; :local t [:pic $exp 7 16]; :local s ("/"); :local exp ("$d$s$year $t"); /ip hotspot user set comment=$exp [find where name="$user"];}; :if ($getxp = 8) do={ /ip hotspot user set comment="$date $exp" [find where name="$user"];}; :if ($getxp > 15) do={ /ip hotspot user set comment=$exp [find where name="$user"];}; /sys sch remove [find where name="$user"]';

        $mode = "";
        if ($expmode == "rem") {
            $onlogin = $onlogin . $lock . "}}";
            $mode = "remove";
        } elseif ($expmode == "ntf") {
            $onlogin = $onlogin . $lock . "}}";
            $mode = "set limit-uptime=1s";
        } elseif ($expmode == "remc") {
            $onlogin = $onlogin . $record . $lock . "}}";
            $mode = "remove";
        } elseif ($expmode == "ntfc") {
            $onlogin = $onlogin . $record . $lock . "}}";
            $mode = "set limit-uptime=1s";
        } elseif ($expmode == "0" && $price != "") {
            $onlogin = ':put (",,' . $price . ',,,noexp,' . $getlock . ',")' . $lock;
        } else {
            $onlogin = "";
        }

        $bgservice = ':local dateint do={:local montharray ( "jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec" );:local days [ :pick $d 4 6 ];:local month [ :pick $d 0 3 ];:local year [ :pick $d 7 11 ];:local monthint ([ :find $montharray $month]);:local month ($monthint + 1);:if ( [len $month] = 1) do={:local zero ("0");:return [:tonum ("$year$zero$month$days")];} else={:return [:tonum ("$year$month$days")];}}; :local timeint do={ :local hours [ :pick $t 0 2 ]; :local minutes [ :pick $t 3 5 ]; :return ($hours * 60 + $minutes) ; }; :local date [ /system clock get date ]; :local time [ /system clock get time ]; :local today [$dateint d=$date] ; :local curtime [$timeint t=$time] ; :foreach i in [ /ip hotspot user find where profile="' . $name . '" ] do={ :local comment [ /ip hotspot user get $i comment]; :local name [ /ip hotspot user get $i name]; :local gettime [:pic $comment 12 20]; :if ([:pic $comment 3] = "/" and [:pic $comment 6] = "/") do={:local expd [$dateint d=$comment] ; :local expt [$timeint t=$gettime] ; :if (($expd < $today and $expt < $curtime) or ($expd < $today and $expt > $curtime) or ($expd = $today and $expt < $curtime)) do={ [ /ip hotspot user ' . $mode . ' $i ]; [ /ip hotspot active remove [find where user=$name] ];}}}';

        try {
            $this->hotspotRepository->addUserProfile($name, $addrpool, $ratelimit, $sharedusers, $onlogin, $parent, $validity);

            $gsch = $this->hotspotRepository->getSchedulerByHotspotpName($name);
            $monid = !empty($gsch[0]['.id']) ? $gsch[0]['.id'] : null;
            if (isset($expmode) && $expmode != "0") {
                if (empty($monid)) {
                    $this->hotspotRepository->addScheduler($name, $randstarttime, $randinterval, $bgservice);
                    $response = [
                        'status' => 'success',
                        'message' => 'Scheduler berhasil ditambahkan',
                        'title' => 'Add Scheduler'
                    ];
                    return response()->json($response);
                } else {
                    $this->hotspotRepository->setScheduler($monid, $name, $randstarttime, $randinterval, $bgservice);
                    $response = [
                        'status' => 'success',
                        'message' => 'Scheduler berhasil diupdate',
                        'title' => 'Set Scheduler'
                    ];
                    return response()->json($response);
                }
            } else {
                if (!empty($monid)) {
                    $this->hotspotRepository->removeScheduller($monid);
                    $response = [
                        'status' => 'success',
                        'message' => 'Scheduler berhasil dihapus',
                        'title' => 'Scheduler Removed'
                    ];
                    return response()->json($response);
                } else {
                    $response = [
                        'status' => 'success',
                        'message' => 'Scheduler tidak ditemukan',
                        'title' => 'Scheduler Not Found'
                    ];
                    return response()->json($response, 404);
                }
            }
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Gagal memproses: ' . $e->getMessage(),
                'title' => 'Error'
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     * @throws \Exception
     */
    public function getProfile(Mikrotik $mikrotik)
    {
       $userprofiles = $this->hotspotRepository->getUserProfile();

        if (request()->ajax()) {
            return DataTables::of($userprofiles)
                ->addIndexColumn()
                ->editColumn('name', function ($data) {
                    return '<span class="badge badge-info" data-toggle="tooltip" data-original-title="Username">' . $data['name'] . '</span>';
                })
                ->editColumn('shared-users', function ($data) {
                    return '<span class="badge badge-primary">' . $data['shared-users'] . ' device</span>';
                })
                ->editColumn('expired-mode', function ($data) {
                    $ponlogin = trim($data['on-login'] ?? '');
                    if (empty($ponlogin)) {
                        $badgeClass = "badge bg-secondary";
                        $badgeLabel = "Unknown";
                    } else {
                        $getexpmode = explode(",", $ponlogin);
                        $expmode = trim($getexpmode[1]);
                        if ($expmode == "rem") {
                            $badgeClass = "badge badge-danger";
                            $badgeLabel = "Remove";
                        } elseif ($expmode == "ntf") {
                            $badgeClass = "badge badge-info";
                            $badgeLabel = "Notice";
                        } elseif ($expmode == "remc") {
                            $badgeClass = "badge badge-success";
                            $badgeLabel = "Remove & Record";
                        } elseif ($expmode == "ntfc") {
                            $badgeClass = "badge badge-primary";
                            $badgeLabel = "Notice & Record";
                        } else {
                            $badgeClass = "badge badge-secondary";
                            $badgeLabel = "N/A";
                        }
                    }
                    return '<span class="' . $badgeClass . '">' . $badgeLabel . '</span>';
                })
                ->editColumn('validity', function ($data) {
                    $ponlogin = trim($data['on-login'] ?? '');
                    if ($ponlogin && isset(explode(",", $ponlogin)[3])) {
                        return explode(",", $ponlogin)[3];
                    } else {
                        return '-';
                    }
                })
                ->editColumn('price', function ($data) {
                    $ponlogin = trim($data['on-login'] ?? '');
                    if ($ponlogin && isset(explode(",", $ponlogin)[2])) {
                        return 'Rp ' . number_format(floatval(explode(",", $ponlogin)[2]), 0, ',', '.');
                         } else {
                        return '-';
                    }
                })
                ->editColumn('selling-price', function ($data) {
                    $ponlogin = trim($data['on-login'] ?? '');
                    if ($ponlogin && isset(explode(",", $ponlogin)[4])) {
                        return 'Rp ' . number_format(floatval(explode(",", $ponlogin)[2]), 0, ',', '.');
                    } else {
                        return '-';
                    }
                })
                ->editColumn('lock-user', function ($data) {
                    $ponlogin = trim($data['on-login'] ?? '');
                    if ($ponlogin && isset(explode(",", $ponlogin)[6])) {
                        if (explode(",", $ponlogin)[6] == 'Enable') {
                            return '<span class="badge badge-info">Enable</span>';
                        } else {
                            return '<span class="badge badge-secondary">Disable</span>';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($data) {
//                    $editUrl = route('edit_user_profile', ['id' => $data['.id']]);
//                    $deleteUrl = route('delete_user_profile', ['id' => $data['.id']]);
                    $editUrl = url('edit_user_profile', ['id' => $data['.id']]);
                    $deleteUrl = url('delete_user_profile', ['id' => $data['.id']]);
                    return '<div class="dropdown">
        <a class="badge badge-primary dropdown-toggle" type="button"
        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
            Action
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="' . $editUrl . '">Edit</a>
            <a class="dropdown-item" href="' . $deleteUrl . '">Delete</a>
        </div>
    </div>';
                })
                 ->rawColumns(['lock-user','selling-price','price','validity','expired-mode','name','shared-users','action'])
                ->make(true);

        }
        return view('mikrotik.hotspot.partials._userprofile',[
            'mikrotik' => $mikrotik,
        ]);
    }
}
