// Global state for escrow flow
let selectedFlow = null;
let currentItemData = null;

/**
 * Initialize escrow flow when button is clicked
 */
function initializeEscrowFlow(button) {
    currentItemData = {
        itemId: button.dataset.itemId,
        itemName: button.dataset.itemName,
        itemPrice: button.dataset.itemPrice
    };

    // Update modal with item details
    document.getElementById('modalItemName').textContent = currentItemData.itemName;
    document.getElementById('modalItemPrice').textContent = parseFloat(currentItemData.itemPrice).toFixed(2);

    // Reset selections
    selectedFlow = null;
    document.querySelectorAll('.flow-option').forEach(el => {
        el.classList.remove('selected');
    });
    document.getElementById('confirmFlowBtn').disabled = true;

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('escrowFlowModal'));
    modal.show();
}

/**
 * Select payment flow
 */
function selectFlow(flowType, element) {
    selectedFlow = flowType;

    // Update UI
    document.querySelectorAll('.flow-option').forEach(el => {
        el.classList.remove('selected');
    });
    element.classList.add('selected');

    // Enable confirm button
    document.getElementById('confirmFlowBtn').disabled = false;
}

/**
 * Confirm and proceed with selected flow
 */
document.getElementById('confirmFlowBtn')?.addEventListener('click', async function() {
    if (!selectedFlow || !currentItemData) {
        alert('Please select a payment flow');
        return;
    }

    const btn = this;
    const spinner = document.getElementById('flowSpinner');
    const btnText = document.getElementById('confirmBtnText');

    // Disable and show loading
    btn.disabled = true;
    spinner.style.display = 'inline-block';
    btnText.textContent = ' Initializing...';

    try {
        // Call initiate_escrow.php
        const response = await fetch('escrow/initiate_escrow.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `item_id=${currentItemData.itemId}&flow_type=${selectedFlow}`
        });

        const data = await response.json();

        if (data.success) {
            // Hide modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('escrowFlowModal'));
            modal.hide();

            // Show success message
            alert(`✅ Success! ${data.item_name} secured in escrow.\nRedirecting to next step...`);

            // Redirect to next step
            setTimeout(() => {
                window.location.href = data.redirect_url;
            }, 1000);
        } else {
            alert('❌ Error: ' + data.error);
            btn.disabled = false;
            spinner.style.display = 'none';
            btnText.textContent = ' Proceed to Escrow';
        }
    } catch (error) {
        alert('❌ Error: ' + error.message);
        btn.disabled = false;
        spinner.style.display = 'none';
        btnText.textContent = ' Proceed to Escrow';
    }
});

/**
 * Handle modal close - reset state
 */
document.getElementById('escrowFlowModal')?.addEventListener('hidden.bs.modal', function() {
    selectedFlow = null;
    currentItemData = null;
    document.querySelectorAll('.flow-option').forEach(el => {
        el.classList.remove('selected');
    });
});