<?php

namespace App\Http\Livewire\Referred;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ReferredComponent extends Component
{
    use WithPagination;
    public $level, $id_search = '', $references = [], $r1 = '', $r2 = '';
    private $referrals = [];
    public $total_n1 = 0, $total_n2 = 0, $totalNs = 0;
    public function mount()
    {
        $this->level =  1;
        $this->id_search =  Auth::user()->currentTeam->id;
        $this->search = '';
        array_push($this->references, ['id' => Auth::user()->currentTeam->id, 'name' => Auth::user()->currentTeam->name, 'level' => 1]);
        $this->calculator();
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        if (Auth::user()->currentTeam->referral_code) {
            $search = $this->search;
            $allReferrals = Team::where('teams.referred_by', $this->id_search)
            ->where('teams.active', '1')
            ->where(function ($query) use ($search) {
                $query->orWhere('teams.name', 'like', '%' . $search . '%');
            })
            ->join('plans', 'plans.id', '=', 'teams.plan_id')
            ->leftJoin(
                DB::raw('(select teams.referred_by,count(*) as referes, sum(plans.price_for_month) as total from teams left join plans on teams.plan_id=plans.id group by teams.referred_by) as U2 '),
                'teams.id',
                '=',
                'U2.referred_by'
            )->select('teams.*', 'plans.price_for_month as mount_refered', 'referes', 'total');
            return view('livewire.referred.referred-component', ['allReferrals' => $allReferrals->paginate(10)]);
        } else {
            return view('livewire.referred.joinReferred-component');
        }
    }
    public function calculator()
    {
        foreach (Team::Select('teams.*', 'plans.price_for_month')->where('teams.referred_by', $this->id_search)->where('active', '1')->join('plans', 'plans.id', '=', 'teams.plan_id')->get() as $reference) {
            $this->total_n1 += $reference->price_for_month;
            foreach (Team::Select('teams.*', 'plans.price_for_month')->where('teams.referred_by', $reference->id)->where('active', '1')->join('plans', 'plans.id', '=', 'teams.plan_id')->get() as $reference2) {
                $this->total_n2 += $reference2->price_for_month;
            }
        }
        $this->total_n1 = $this->total_n1 * 0.10;
        $this->total_n2 = $this->total_n2 * 0.05;
        $this->totalNs = $this->total_n1 + $this->total_n2;
    }
    public function nextLevel($id_search, $level)
    {
        $this->search = '';
        $this->page = 1;
        $this->id_search = $id_search;
        $this->level = $level;
        $user = Team::find($id_search);
        switch ($this->level) {
            case 2:
                array_push($this->references, ['id' => $user->id, 'name' => '->' . $user->name, 'level' => 2]);
                break;

            default:
                break;
        }
    }
    public function referredby(Request $request,$code){
        if (!$request->hasCookie('referral')) {
            $cookie = cookie('referral', $code, 60 * 24 * 7);
    
            return redirect('/register')->withCookie($cookie);
        }
    
        return redirect('/register');
    }
    public function levelCalculate($id)
    {
        $total = 0;
        $nivel2 = 0;
        switch ($this->level) {
            case 1:
                foreach (Team::Select('teams.*', 'plans.price_for_month')->where('teams.referred_by', $id)->where('active', '1')->join('plans', 'plans.id', '=', 'teams.plan_id')->get() as $reference2) {
                    $nivel2 += $reference2->price_for_month;
                }
                $total = ($nivel2 * 0.05);
                break;
            default:
                break;
        }
        return $total;
    }

    public function accept()
    {
        DB::beginTransaction();
        try {
            Auth::user()->currentTeam->update([
                'referral_code' => Team::getUniqueReferralCode()
            ]);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Exíto ya podes comenzar a ofrecer nuestro producto y aumentar tus ganancias!', 'refresh' => 1]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
    }
}
