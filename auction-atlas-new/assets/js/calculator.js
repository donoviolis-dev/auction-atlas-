/**
 * Auction Atlas - Fee Calculator
 * 
 * Live JavaScript calculations for auction fee estimation.
 * Handles hammer price, premium, deposit, and VAT calculations.
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== Fee Calculator =====
    var calcForm = document.getElementById('fee-calc-form');
    if (!calcForm) return;
    
    var hammerInput = document.getElementById('hammer-price');
    var premiumInput = document.getElementById('premium-percent');
    var depositInput = document.getElementById('deposit-percent');
    var vatInput = document.getElementById('vat-percent');
    
    var hammerSlider = document.getElementById('hammer-slider');
    var premiumSlider = document.getElementById('premium-slider');
    var depositSlider = document.getElementById('deposit-slider');
    var vatSlider = document.getElementById('vat-slider');
    
    // Output elements
    var resultPremium = document.getElementById('result-premium');
    var resultVat = document.getElementById('result-vat');
    var resultDeposit = document.getElementById('result-deposit');
    var resultTotal = document.getElementById('result-total');
    var resultBalance = document.getElementById('result-balance');
    
    function formatCurrency(amount) {
        return 'R ' + amount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }
    
    function calculate() {
        var hammer = parseFloat(hammerInput.value) || 0;
        var premiumPct = parseFloat(premiumInput.value) || 0;
        var depositPct = parseFloat(depositInput.value) || 0;
        var vatPct = parseFloat(vatInput.value) || 15;
        
        // Calculate premium amount
        var premiumAmount = hammer * (premiumPct / 100);
        
        // Calculate VAT on premium
        var vatAmount = premiumAmount * (vatPct / 100);
        
        // Calculate deposit
        var depositAmount = hammer * (depositPct / 100);
        
        // Total cost
        var totalCost = hammer + premiumAmount + vatAmount;
        
        // Balance due after deposit
        var balanceDue = totalCost - depositAmount;
        
        // Update display
        if (resultPremium) resultPremium.textContent = formatCurrency(premiumAmount);
        if (resultVat) resultVat.textContent = formatCurrency(vatAmount);
        if (resultDeposit) resultDeposit.textContent = formatCurrency(depositAmount);
        if (resultTotal) resultTotal.textContent = formatCurrency(totalCost);
        if (resultBalance) resultBalance.textContent = formatCurrency(Math.max(0, balanceDue));
        
        // Update premium percentage display
        var premiumDisplay = document.getElementById('premium-display');
        if (premiumDisplay) premiumDisplay.textContent = premiumPct + '%';
        
        var depositDisplay = document.getElementById('deposit-display');
        if (depositDisplay) depositDisplay.textContent = depositPct + '%';
        
        var vatDisplay = document.getElementById('vat-display');
        if (vatDisplay) vatDisplay.textContent = vatPct + '%';
        
        var hammerDisplay = document.getElementById('hammer-display');
        if (hammerDisplay) hammerDisplay.textContent = formatCurrency(hammer);
    }
    
    // Sync sliders with inputs
    function syncSliderToInput(slider, input) {
        if (slider && input) {
            slider.addEventListener('input', function() {
                input.value = this.value;
                calculate();
            });
            input.addEventListener('input', function() {
                slider.value = this.value;
                calculate();
            });
        }
    }
    
    syncSliderToInput(hammerSlider, hammerInput);
    syncSliderToInput(premiumSlider, premiumInput);
    syncSliderToInput(depositSlider, depositInput);
    syncSliderToInput(vatSlider, vatInput);
    
    // Also listen for direct input changes
    [hammerInput, premiumInput, depositInput, vatInput].forEach(function(input) {
        if (input) {
            input.addEventListener('input', calculate);
            input.addEventListener('change', calculate);
        }
    });
    
    // Initial calculation
    calculate();
});
