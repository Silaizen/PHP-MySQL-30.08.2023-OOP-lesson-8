<?php
class ATM {
    private $denominations = [500, 200, 100, 50, 20, 10, 5, 2, 1]; 
    private $noteCounts = []; 
    private $minWithdrawalAmount = 10; 
    private $maxWithdrawalNotes = 10;
    private $dataPath = 'data/atm_data.json'; 

    public function __construct() {
       
        $this->loadData();
    }

    
    private function loadData() {
        if (file_exists($this->dataPath)) {
            $data = file_get_contents($this->dataPath);
            $loadedData = json_decode($data, true);
    
            if (is_array($loadedData)) {
                $this->noteCounts = $loadedData;
            } else {
               
                foreach ($this->denominations as $denomination) {
                    $this->noteCounts[$denomination] = 0;
                }
            }
        } else {
           
            foreach ($this->denominations as $denomination) {
                $this->noteCounts[$denomination] = 0;
            }
        }
    }
   
    
    
    
    
    
    

    
    private function saveData() {
        $data = json_encode($this->noteCounts);
        if (file_put_contents($this->dataPath, $data) === false) {
            error_log("Ошибка при записи в файл: " . $this->dataPath);
        }
    }

    
    public function loadMoney($denomination, $count) {
        if (in_array($denomination, $this->denominations) && $count > 0) {
            $this->noteCounts[$denomination] += $count;
            $this->saveData(); 
        }
    }

   
    public function inputMoney($denomination, $count) {
        if (in_array($denomination, $this->denominations) && $count > 0) {
            $this->noteCounts[$denomination] += $count;
            $this->saveData(); 
        }
    }

    
    public function withdrawMoney($amount) {
        if ($amount < $this->minWithdrawalAmount) {
            return "Сумма слишком мала для снятия.";
        }

        $withdrawnNotes = [];
        $remainingAmount = $amount;

        foreach ($this->denominations as $denomination) {
            $notesToWithdraw = min(
                floor($remainingAmount / $denomination),
                $this->maxWithdrawalNotes,
                $this->noteCounts[$denomination]
            );

            if ($notesToWithdraw > 0) {
                $withdrawnNotes[$denomination] = $notesToWithdraw;
                $this->noteCounts[$denomination] -= $notesToWithdraw;
                $remainingAmount -= $denomination * $notesToWithdraw;
            }

            if ($remainingAmount === 0) {
                $this->saveData(); 
    return $withdrawnNotes;
            }
        }

        if ($remainingAmount === 0) {
            $this->saveData(); 
            return $withdrawnNotes;
        } else {
           
            return "Невозможно выдать указанную сумму.";
        }
    }

    
    public function setMinWithdrawalAmount($minAmount) {
        $this->minWithdrawalAmount = $minAmount;
        $this->saveSettings();
    }

    
    public function setMaxWithdrawalNotes($maxNotes) {
        $this->maxWithdrawalNotes = $maxNotes;
        $this->saveSettings();
    }

    
    public function getNoteCounts() {
        return $this->noteCounts;
    }

    
    public function getMinWithdrawalAmount() {
        return $this->minWithdrawalAmount;
    }

    
    public function getMaxWithdrawalNotes() {
        return $this->maxWithdrawalNotes;
    }

    
    private function saveSettings() {
        $settings = [
            'minWithdrawalAmount' => $this->minWithdrawalAmount,
            'maxWithdrawalNotes' => $this->maxWithdrawalNotes,
        ];
        $data = json_encode($settings);
        file_put_contents($this->dataPath, $data);
    }
}
?>