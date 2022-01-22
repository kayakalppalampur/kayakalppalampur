<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DietChart extends Model
{
    //

    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;

    protected $fillable = [
        'patient_id',
        'start_date',
        'repeats',
        'notes',
        'status',
        'end_date',
        'created_by',
        'booking_id',
        'bill_id'
    ];

    protected $table = 'diet_chart';
    
    public function bill()
    {
        return $this->belongsTo('App\Bill', 'bill_id');
    }


    public static function getRules()
    {
        return [
            'patient_id' => 'required',
            'start_date' => 'required',/*
            'repeats' => 'required',
            'notes' => 'required'*/
        ];
    }

    public static function getCurrentTimeClass($type)
    {
        $timestamp = self::getCurrentTime();
        $chart_times = self::getTimes($type);
        $chart_times['start'] = strtotime(date("H:i:s", strtotime($chart_times['start'])));
        $chart_times['end'] = strtotime(date("H:i:s", strtotime($chart_times['end'])));

        if ($timestamp >= $chart_times['start'] && $timestamp <= $chart_times['end']) {
            return true;
        }

        return false;
    }

    public static function getCurrentTime()
    {
        /* $time = Carbon::createFromTime(date('H'), date("i"), date("s"))->setTimezone(env('TIMEZONE'))->toDateTimeString();

         $time = strtotime(date("now"));*/
        /*
                $time = new \DateTime('now', new \DateTimeZone('UTC'));
                // than convert it to IST by
                $time->setTimezone(new \DateTimeZone('IST'));*/
        return strtotime("+5 hours 30 minutes");
    }

    public static function getTimes($type)
    {
        $times = [
            'start' => DietChartItems::TYPE_BREAKFAST_START_TIME,
            'end' => DietChartItems::TYPE_BREAKFAST_TIME
        ];
        if ($type == DietChartItems::TYPE_LUNCH) {
            $times = [
                'start' => DietChartItems::TYPE_LUNCH_START_TIME,
                'end' => DietChartItems::TYPE_LUNCH_TIME
            ];
        } elseif ($type == DietChartItems::TYPE_POST_LUNCH) {
            $times = [
                'start' => DietChartItems::TYPE_POST_LUNCH_START_TIME,
                'end' => DietChartItems::TYPE_POST_LUNCH_TIME
            ];
        } elseif ($type == DietChartItems::TYPE_DINNER) {
            $times = [
                'start' => DietChartItems::TYPE_DINNER_START_TIME,
                'end' => DietChartItems::TYPE_DINNER_TIME
            ];
        } elseif ($type == DietChartItems::TYPE_SPECIAL) {
            $times = [
                'start' => DietChartItems::TYPE_SPECIAL_START_TIME,
                'end' => DietChartItems::TYPE_SPECIAL_TIME
            ];
        }

        return $times;
    }

    public static function getCurrentTimeClassg($type)
    {
        $time = Carbon::createFromTime(date('H'), date("i"), date("s"))->setTimezone(env('TIMEZONE'))->toDateTimeString();/*
        $time = date("H:i:s");*/
        $time = date("H:i:s", strtotime($time));


        if ($type == DietChartItems::TYPE_BREAKFAST) {
            if ($time >= DietChartItems::TYPE_BREAKFAST_TIME && $time < DietChartItems::TYPE_LUNCH_TIME) {
                return true;
            }
        } elseif ($type == DietChartItems::TYPE_LUNCH) {
            if ($time >= DietChartItems::TYPE_LUNCH_TIME && $time < DietChartItems::TYPE_POST_LUNCH_TIME) {
                return true;
            }
        } elseif ($type == DietChartItems::TYPE_POST_LUNCH) {
            if ($time >= DietChartItems::TYPE_POST_LUNCH_TIME && $time < DietChartItems::TYPE_DINNER_TIME) {
                return true;
            }
        } elseif ($type == DietChartItems::TYPE_DINNER) {
            if ($time >= DietChartItems::TYPE_DINNER_TIME && $time < DietChartItems::TYPE_SPECIAL_TIME) {
                return true;
            }
        } elseif ($type == DietChartItems::TYPE_SPECIAL) {
            if ($time >= DietChartItems::TYPE_SPECIAL_TIME) {
                return true;
            }
        }
        return false;
    }

    public static function getDailyStatus($type)
    {
        $total_patients = DietChart::where('start_date', date("Y-m-d"))->get();
        $total_patients_count = DietChart::where('start_date', date("Y-m-d"))->count();

        $timestamp = self::getCurrentTime();

        $data = [
            'total_patient' => $total_patients_count,
            'had_meal' => "upcoming",
            'pending' => "upcoming",
            'not_come' => "upcoming"
        ];
        $chart_times = self::getTimes($type);
        $chart_times['start'] = strtotime(date("H:i:s", strtotime($chart_times['start'])));
        $chart_times['end'] = strtotime(date("H:i:s", strtotime($chart_times['end'])));

        if ($timestamp >= $chart_times['start']) {
            $attr = self::getChartAttribute($type);
            $data['had_meal'] = DietDailyStatus::where("date", date("Y-m-d"))->where($attr, '!=', 0)->count();
            $p = 0;
            foreach ($total_patients as $total_patient) {
                $daily_diet = DietDailyStatus::where("date", date("Y-m-d"))->where("diet_id", $total_patient->id)->first();
                if ($daily_diet != null) {
                    if ($daily_diet->$attr == 0) {
                        $p++;
                    }
                } else {
                    $p++;
                }
            }
            if ($timestamp > $chart_times['end']) {
                $data['pending'] = "";
                $data['not_come'] = $p;
            } else {
                $data['pending'] = $p;
                $data['not_come'] = "";
            }
        }

        return $data;
    }

    public static function getChartAttribute($type)
    {
        $attr = "is_breakfast";
        if ($type == DietChartItems::TYPE_LUNCH) {
            $attr = "is_lunch";
        } elseif ($type == DietChartItems::TYPE_POST_LUNCH) {
            $attr = "is_post_lunch";
        } elseif ($type == DietChartItems::TYPE_DINNER) {
            $attr = "is_dinner";
        } elseif ($type == DietChartItems::TYPE_SPECIAL) {
            $attr = "is_special";
        }

        return $attr;

    }

    public static function isEditable($id, $date)
    {
        $diet = self::find($id);
        return true;
        if ($date >= date("Y-m-d") && $diet->booking->status == Booking::STATUS_COMPLETED) {
            /*if ($diet->created_by == \Auth::user()->id)*/
            return true;
        }
        return false;
    }

    public static function discharge($id, $b_id, $status)
    {
        $diet_charts = self::where("patient_id", $id)->where('booking_id', $b_id)->where('start_date', '>', (string)date("Y-m-d"))->get();
        if ($diet_charts->count() > 0) {
            foreach ($diet_charts as $diet_chart) {
                $diet_chart->deletePreviousItems();
                $diet_chart->delete();
            }
        }

        $old_diet_charts = self::where("patient_id", $id)->where('booking_id', $b_id)->where('status', self::STATUS_PENDING)->get();

        if ($old_diet_charts->count() > 0) {
            foreach ($old_diet_charts as $old_diet_chart) {
                $old_diet_chart->update([
                    'status' => $status,
                    'booking_id' => $b_id
                ]);
            }
        }

    }

    public static function customDelete($id, $b_id)
    {
        $diet_charts = self::where("patient_id", $id)->where('booking_id', $b_id)->where('start_date', '>', (string)date("Y-m-d"))->get();
        if ($diet_charts->count() > 0) {
            foreach ($diet_charts as $diet_chart) {
                $diet_chart->deletePreviousItems();
                $diet_chart->delete();
            }
        }

        $old_diet_charts = self::where("patient_id", $id)->where('booking_id', $b_id)->get();

        if ($old_diet_charts->count() > 0) {
            foreach ($old_diet_charts as $old_diet_chart) {
                $old_diet_chart->delete();
            }
        }

    }

    public static function getDailyServings($type, $date)
    {
        $patients = DietChart::select('diet_chart.*')/*->where('diet_chart.status', DietChart::STATUS_PENDING)*/
        ->where('diet_chart.start_date', (string)$date)
            ->join('bookings', 'bookings.id', '=', 'diet_chart.booking_id')
            ->join('user_profiles', 'user_profiles.id', '=', 'bookings.profile_id')
            ->orderBy('user_profiles.created_at', 'DESC')->get();

        $diet_chart = [];
        $i = 1;
        foreach ($patients as $patient) {
            $dietDailyStatus = DietDailyStatus::where('diet_id', $patient->id)->where('date', (string)date("Y-m-d"))->first();
            $breakfast = false;
            $lunch = false;
            $post_lunch = false;
            $dinner = false;
            $special = false;

            $served = false;
            if ($dietDailyStatus != null) {
                if ($type == DietChartItems::TYPE_BREAKFAST) {
                    if ($dietDailyStatus->is_breakfast != 0) {
                        $breakfast = true;
                        $served = true;
                    }
                } elseif ($type == DietChartItems::TYPE_LUNCH) {
                    if ($dietDailyStatus->is_lunch != 0) {
                        $lunch = true;
                        $served = true;
                    }
                } elseif ($type == DietChartItems::TYPE_POST_LUNCH) {
                    if ($dietDailyStatus->is_post_lunch != 0) {
                        $post_lunch = true;
                        $served = true;
                    }
                } elseif ($type == DietChartItems::TYPE_DINNER) {
                    if ($dietDailyStatus->is_dinner != 0) {
                        $dinner = true;
                        $served = true;
                    }
                } elseif ($type == DietChartItems::TYPE_SPECIAL) {
                    if ($dietDailyStatus->is_special != 0) {
                        $special = true;
                        $served = true;
                    }
                }
            }


            /*if ($breakfast == false && $lunch == false && $post_lunch == false && $dinner == false && $special == false) {*/
            $items = DietChartItems::where('diet_id', $patient->id)
                ->where('type_id', $type)
                ->get();

            $diet_chart_ar = [
                'sno' => $i,
                'kid' => $patient->booking->userProfile->kid,
                'patient_name' => $patient->booking->userProfile->first_name . ' ' . $patient->booking->userProfile->last_name,
                'booking_id' => $patient->booking->id,
                'diet_id' => $patient->id,
                'is_served' => $served == true ? 'Yes' : 'No'
            ];

            $j = 1;
            foreach ($items as $item) {
                $diet_chart_ar['item_' . $j] = $item->item->name;
                $j++;
            }

            while ($j <= 7) {
                $diet_chart_ar['item_' . $j] = "";
                $j++;
            }
            $diet_chart_ar['notes'] = $patient->notes;
            $diet_chart[] = $diet_chart_ar;
            $i++;
            //}
        }


        return $diet_chart;
    }

    public function dailyDiets()
    {
        return $this->hasMany("App\DietDailyStatus", "diet_id");
    }

    public function patient()
    {
        return $this->belongsTo("App\User", "patient_id");
    }

    public function booking()
    {
        return $this->belongsTo("App\Booking", "booking_id");
    }

    public function items()
    {
        return $this->hasMany("App\DietChartItems", "diet_id");
    }

    public function setData($data)
    {
        $this->booking_id = $data->get("booking_id");
        $this->patient_id = $data->get("patient_id");
        $this->created_by = \Auth::user()->id;
        $this->start_date = $data->get("start_date");
        $this->repeats = 0;/*$data->get("repeats") != null ? $data->get("repeats") : 0;*/
        $this->notes = $data->get("notes");
        $this->end_date = date("Y-m-d", strtotime($this->start_date . ' +' . $this->repeats . ' days'));
        $this->status = self::STATUS_PENDING;
        return $this;
    }

    public function getItems($type = DietChartItems::TYPE_BREAKFAST)
    {
        $items = DietChartItems::where([
            'type_id' => $type,
            'diet_id' => $this->id
        ])->get();

        if ($items != null)
            return $items;
        return [];
    }

    public function getDietStatus($type)
    {
        $date = (string)date("Y-m-d");
        $diet_status = DietDailyStatus::where([
            'date' => $date,
            'diet_id' => $this->id])->first();
        if ($diet_status != null) {
            if ($type == DietChartItems::TYPE_BREAKFAST) {
                if ($diet_status->is_breakfast != DietDailyStatus::STATUS_PENDING) {
                    return true;
                }
            } elseif ($type == DietChartItems::TYPE_LUNCH) {
                if ($diet_status->is_lunch != DietDailyStatus::STATUS_PENDING) {
                    return true;
                }
            } elseif ($type == DietChartItems::TYPE_POST_LUNCH) {
                if ($diet_status->is_post_lunch != DietDailyStatus::STATUS_PENDING) {
                    return true;
                }
            } elseif ($type == DietChartItems::TYPE_DINNER) {
                if ($diet_status->is_dinner != DietDailyStatus::STATUS_PENDING) {
                    return true;
                }
            } elseif ($type == DietChartItems::TYPE_SPECIAL) {
                if ($diet_status->is_special != DietDailyStatus::STATUS_PENDING) {
                    return true;
                }
            }
        }

        return false;
    }

    public function saveItems($data)
    {
        $this->deletePreviousItems();

        $i = 1;
        foreach (KitchenItem::all() as $item) {
            $breakfast_item = $data->get("item_" . $item->id . "-type_" . DietChartItems::TYPE_BREAKFAST);
            if ($breakfast_item != null) {
                $diet_item = $this->saveItem($data->get("item_" . $item->id . "-type_" . DietChartItems::TYPE_BREAKFAST), DietChartItems::TYPE_BREAKFAST);
            }
            $lunch_item = $data->get("item_" . $item->id . "-type_" . DietChartItems::TYPE_LUNCH);
            if ($lunch_item != null) {
                $diet_item = $this->saveItem($data->get("item_" . $item->id . "-type_" . DietChartItems::TYPE_LUNCH), DietChartItems::TYPE_LUNCH);
            }
            $post_lunch_item = $data->get("item_" . $item->id . "-type_" . DietChartItems::TYPE_POST_LUNCH);
            if ($post_lunch_item != null) {
                $diet_item = $this->saveItem($data->get("item_" . $item->id . "-type_" . DietChartItems::TYPE_POST_LUNCH), DietChartItems::TYPE_POST_LUNCH);
            }
            $dinner_item = $data->get("item_" . $item->id . "-type_" . DietChartItems::TYPE_DINNER);
            if ($dinner_item != null) {
                $diet_item = $this->saveItem($data->get("item_" . $item->id . "-type_" . DietChartItems::TYPE_DINNER), DietChartItems::TYPE_DINNER);
            }
            $special_item = $data->get("item_" . $item->id . "-type_" . DietChartItems::TYPE_SPECIAL);
            if ($special_item != null) {
                $diet_item = $this->saveItem($data->get("item_" . $item->id . "-type_" . DietChartItems::TYPE_SPECIAL), DietChartItems::TYPE_SPECIAL);
            }
            $i++;
        }
    }

    public function deletePreviousItems()
    {
        $items = $this->items;
        if ($items->count() > 0) {
            foreach ($items as $item) {
                $item->delete();
            }
        }

    }

    public function saveItem($item_id, $type)
    {
        $item = KitchenItem::find($item_id);
        if ($item) {
            $diet_item = DietChartItems::where([
                'diet_id' => $this->id,
                'item_id' => $item_id,
                'type_id' => $type,
            ])->first();

            if ($diet_item == null) {
                $diet_item = DietChartItems::create([
                    'diet_id' => $this->id,
                    'time' => DietChartItems::getTimeOptions($type),
                    'item_id' => $item_id,
                    'type_id' => $type,
                    'created_by' => \Auth::user()->id,
                    'item_price' => $item->price
            ]);
            }
        }
    }

    public function getDietPrice($type)
    {
        $diet_items = DietChartItems::where([
            'diet_id' => $this->id,
            'type_id' => $type,
        ])->get();
        $price = 0;
        foreach ($diet_items as $diet_item) {
            if (isset($diet_item->item->price))
                $price = $price + $diet_item->item->price;
        }
        return $price;
    }

    public function getItemArray()
    {
        $ar = [];
        foreach (DietChartItems::getTypeOptions() as $id => $type) {
            $items = DietChartItems::where("diet_id", $this->id)->where("type_id", $id)->get();
            $item_ar = [];
            foreach ($items as $item) {
                if ($item->item != null) {
                    $item_ar[] = $item->item->name;
                }
            }

            $ar[$id] = $item_ar;
        }
        $ar['notes'] = $this->notes;
        $ar['id'] = $this->id;
        return $ar;
    }

    public function checkSelected($id, $type, $item_no)
    {
        $diet_chart_items = DietChartItems::where('diet_id', $this->id)->where('type_id', $type)->get();

        $diet_chart_items_ar = [];

        foreach ($diet_chart_items as $diet_chart_item) {
            $diet_chart_items_ar[] = $diet_chart_item->toArray();
        }

        if (isset($diet_chart_items_ar[$item_no])) {
            $diet_item = $diet_chart_items_ar[$item_no];

            if ($diet_item['item_id'] == $id) {
                return 'selected';
            }
        }

        return "";
    }

    public function getStatus($type)
    {
        $daily_diet = DietDailyStatus::where('diet_id', $this->id)->first();
        $timestamp = self::getCurrentTime();
        $chart_times = self::getTimes($type);


        $chart_times['start'] = strtotime(date("H:i:s", strtotime($chart_times['start'])));
        $chart_times['end'] = strtotime(date("H:i:s", strtotime($chart_times['end'])));

        if ($daily_diet != null) {
            $attr = $this->getChartAttribute($type);

            if ($timestamp > $chart_times['end'])
                return $daily_diet->$attr != 0 ? "Done" : "Didn't Came";

            return $daily_diet->$attr != 0 ? "Done" : "Pending";
        }

        if ($timestamp > $chart_times['end'])
            return "Didn't Came";

        return "Pending";
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::diet-chart',
            'Laralum::kitchen-patient.diet-chart',
            'Laralum::patient-diet-chart'
        ];
    }

    public static function getRequirementsRoutesArray()
    {
        return [
            'Laralum::kitchen-item.requirements',
            'Laralum::kitchen-item.request',
            'Laralum::kitchen.selected_item.request'
        ];
    }

    public static function getMealStatusRoutesArray()
    {
        return [
            'Laralum::meal-status',
        ];
    }

    public static function getMealServingsRoutesArray()
    {
        return [
            'Laralum::meal-servings',
            'Laralum::meal-servings.ajax',
            'Laralum::meal.servings.ajax'
        ];
    }
}


