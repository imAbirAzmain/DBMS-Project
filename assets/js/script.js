document.addEventListener('DOMContentLoaded', function () {
    /* --- Shared Functions --- */

    const toastElement = document.getElementById('prototypeToast');
    const showPrototypeToast = function (message) {
        if (!toastElement || !window.bootstrap) {
            return;
        }
        const messageElement = toastElement.querySelector('[data-toast-message]');
        if (messageElement) {
            messageElement.textContent = message;
        }
        window.bootstrap.Toast.getOrCreateInstance(toastElement).show();
    };

    /* --- Shared responsive sidebar controls --- */

    const sidebar = document.getElementById('appSidebar');
    const toggleButton = document.querySelector('[data-sidebar-toggle]');
    const closeButtons = document.querySelectorAll('[data-sidebar-close]');

    if (sidebar && toggleButton) {
        const setSidebarState = function (isOpen) {
            sidebar.classList.toggle('is-open', isOpen);
            document.body.classList.toggle('sidebar-open', isOpen);
            toggleButton.setAttribute('aria-expanded', String(isOpen));
        };

        toggleButton.addEventListener('click', function () {
            setSidebarState(!sidebar.classList.contains('is-open'));
        });

        sidebar.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.matchMedia('(max-width: 991.98px)').matches) {
                    setSidebarState(false);
                }
            });
        });

        window.addEventListener('resize', function () {
            if (window.matchMedia('(min-width: 992px)').matches) {
                setSidebarState(false);
            }
        });
    }
    
    closeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            setSidebarState(false);
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            setSidebarState(false);
        }
    });

    /* --- Shared table filtering --- */

    document.querySelectorAll('[data-table-search]').forEach(function (searchInput) {
        const tableSelector = searchInput.dataset.tableSearch;
        const table = document.querySelector(tableSelector);

        if (!table) {
            return;
        }

        const tableLabel = table.dataset.tableLabel || 'orders';
        const rows = Array.from(table.querySelectorAll('tbody [data-search-row]'));
        const emptyState = table.querySelector('[data-empty-state]');
        const countElement = document.querySelector('[data-table-count="' + tableSelector + '"]');
        const filters = Array.from(document.querySelectorAll('[data-table-filter]')).filter(function (filter) {
            return filter.dataset.tableFilter === tableSelector;
        });

        const applyTableFilters = function () {
            const searchTerm = searchInput.value.trim().toLowerCase();
            let visibleRows = 0;

            rows.forEach(function (row) {
                const matchesSearch = row.textContent.toLowerCase().includes(searchTerm);
                const matchesFilters = filters.every(function (filter) {
                    const selectedValue = filter.value;
                    const fieldName = filter.dataset.filterKey;

                    return selectedValue === 'all' || row.dataset[fieldName] === selectedValue;
                });
                const isVisible = matchesSearch && matchesFilters;

                row.hidden = !isVisible;

                if (isVisible) {
                    visibleRows += 1;
                }
            });

            if (emptyState) {
                emptyState.hidden = visibleRows !== 0;
            }

            if (countElement) {
                countElement.textContent = visibleRows === 0
                    ? 'Showing 0 of ' + rows.length + ' ' + tableLabel
                    : 'Showing 1–' + visibleRows + ' of ' + rows.length + ' ' + tableLabel;
            }
        };

        searchInput.addEventListener('input', applyTableFilters);
        filters.forEach(function (filter) {
            filter.addEventListener('change', applyTableFilters);
        });
    });

    /* --- Page-specific modal and form logic --- */

    const viewOrderModal = document.getElementById('viewOrderModal');

    if (viewOrderModal) {
        viewOrderModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const detailValues = {
                id: trigger.dataset.orderId,
                buyer: trigger.dataset.orderBuyer,
                description: trigger.dataset.orderDescription,
                style: trigger.dataset.orderStyle,
                quantity: trigger.dataset.orderQuantity,
                date: trigger.dataset.orderDate,
                delivery: trigger.dataset.orderDelivery,
                finalBill: trigger.dataset.orderFinalBill,
                paid: trigger.dataset.orderPaid,
                remaining: trigger.dataset.orderRemaining,
                paymentMethod: trigger.dataset.orderPaymentMethod,
                paymentDate: trigger.dataset.orderPaymentDate,
                status: trigger.dataset.orderStatus
            };

            Object.keys(detailValues).forEach(function (key) {
                const detailElement = viewOrderModal.querySelector('[data-order-detail="' + key + '"]');

                if (detailElement) {
                    detailElement.textContent = detailValues[key] || '—';
                }
            });

            const statusElement = viewOrderModal.querySelector('[data-order-detail="status"]');

            if (statusElement) {
                statusElement.className = 'status-badge status-badge--' + (trigger.dataset.orderStatusClass || 'muted');
            }
        });
    }

    const ordersForm = document.querySelector('[data-orders-form]');

    if (ordersForm) {
        ordersForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const addOrderModal = document.getElementById('addOrderModal');

            if (addOrderModal && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(addOrderModal).hide();
            }

            ordersForm.reset();
            showPrototypeToast('Add Order is available as a UI preview only.');
        });
    }

    const viewStyleModal = document.getElementById('viewOrderStyleModal');

    if (viewStyleModal) {
        viewStyleModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const detailValues = {
                orderId: trigger.dataset.styleOrderId,
                id: trigger.dataset.styleId,
                name: trigger.dataset.styleName,
                color: trigger.dataset.styleColor,
                size: trigger.dataset.styleSize,
                quantity: trigger.dataset.styleQuantity,
                orderDescription: trigger.dataset.styleOrderDescription
            };

            Object.keys(detailValues).forEach(function (key) {
                viewStyleModal.querySelectorAll('[data-style-detail="' + key + '"]').forEach(function (detailElement) {
                    detailElement.textContent = detailValues[key] || '—';
                });
            });

            const colorSwatch = viewStyleModal.querySelector('[data-style-detail-swatch]');

            if (colorSwatch) {
                colorSwatch.className = 'style-detail-swatch style-swatch--' + (trigger.dataset.styleColorClass || 'blue');
            }
        });
    }

    const styleForm = document.querySelector('[data-order-styles-form]');

    if (styleForm) {
        styleForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const addStyleModal = document.getElementById('addOrderStyleModal');

            if (addStyleModal && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(addStyleModal).hide();
            }

            styleForm.reset();
            showPrototypeToast('Add Style is available as a UI preview only.');
        });
    }

    const viewBuyerModal = document.getElementById('viewBuyerModal');

    if (viewBuyerModal) {
        viewBuyerModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const detailValues = {
                id: trigger.dataset.buyerId,
                name: trigger.dataset.buyerName,
                brand: trigger.dataset.buyerBrand,
                initials: trigger.dataset.buyerInitials,
                contact: trigger.dataset.buyerContact,
                email: trigger.dataset.buyerEmail,
                account: trigger.dataset.buyerAccount,
                address: trigger.dataset.buyerAddress,
                order: trigger.dataset.buyerOrder,
                shipment: trigger.dataset.buyerShipment,
                paymentStatus: trigger.dataset.buyerPaymentStatus
            };

            Object.keys(detailValues).forEach(function (key) {
                viewBuyerModal.querySelectorAll('[data-buyer-detail="' + key + '"]').forEach(function (detailElement) {
                    detailElement.textContent = detailValues[key] || '—';
                });
            });

            const paymentStatus = viewBuyerModal.querySelector('[data-buyer-detail="paymentStatus"]');

            if (paymentStatus) {
                paymentStatus.className = 'status-badge status-badge--' + (trigger.dataset.buyerStatusClass || 'muted');
            }
        });
    }

    const buyersForm = document.querySelector('[data-buyers-form]');

    if (buyersForm) {
        buyersForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const addBuyerModal = document.getElementById('addBuyerModal');

            if (addBuyerModal && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(addBuyerModal).hide();
            }

            buyersForm.reset();
            showPrototypeToast('Add Buyer is available as a UI preview only.');
        });
    }

    const viewShipmentModal = document.getElementById('viewShipmentModal');

    if (viewShipmentModal) {
        viewShipmentModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const detailValues = {
                id: trigger.dataset.shipmentId,
                trackingNumber: trigger.dataset.shipmentTrackingNumber,
                estimatedDelivery: trigger.dataset.shipmentEstimatedDelivery,
                destination: trigger.dataset.shipmentDestination,
                shippedDate: trigger.dataset.shipmentShippedDate,
                status: trigger.dataset.shipmentStatus,
                sourcePackageId: trigger.dataset.shipmentSourcePackageId,
                buyerName: trigger.dataset.shipmentBuyerName
            };

            Object.keys(detailValues).forEach(function (key) {
                const detailElement = viewShipmentModal.querySelector('[data-shipment-detail="' + key + '"]');

                if (detailElement) {
                    detailElement.textContent = detailValues[key] || '—';
                }
            });

            const statusElement = viewShipmentModal.querySelector('[data-shipment-detail="status"]');

            if (statusElement) {
                statusElement.className = 'status-badge status-badge--' + (trigger.dataset.shipmentStatusClass || 'muted');
            }
        });
    }

    const shipmentForm = document.querySelector('[data-shipment-form]');

    if (shipmentForm) {
        shipmentForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const addShipmentModal = document.getElementById('addShipmentModal');
            if (addShipmentModal && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(addShipmentModal).hide();
            }
            shipmentForm.reset();
            showPrototypeToast('Add Shipment is available as a UI preview only.');
        });
    }

    const viewPackagingModal = document.getElementById('viewPackagingModal');

    if (viewPackagingModal) {
        viewPackagingModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const detailValues = {
                id: trigger.dataset.packagingId,
                sourceLot: trigger.dataset.packagingSourceLot,
                date: trigger.dataset.packagingDate,
                type: trigger.dataset.packagingType,
                quantityPerPack: trigger.dataset.packagingQuantityPerPack,
                totalPackage: trigger.dataset.packagingTotalPackage,
                weightPerPack: trigger.dataset.packagingWeightPerPack,
                status: trigger.dataset.packagingStatus
            };

            Object.keys(detailValues).forEach(function (key) {
                const detailElement = viewPackagingModal.querySelector('[data-packaging-detail="' + key + '"]');

                if (detailElement) {
                    detailElement.textContent = detailValues[key] || '—';
                }
            });

            const statusElement = viewPackagingModal.querySelector('[data-packaging-detail="status"]');

            if (statusElement) {
                statusElement.className = 'status-badge status-badge--' + (trigger.dataset.packagingStatusClass || 'muted');
            }
        });
    }

    const packagingForm = document.querySelector('[data-packaging-form]');

    if (packagingForm) {
        packagingForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const addPackagingModal = document.getElementById('addPackagingModal');
            if (addPackagingModal && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(addPackagingModal).hide();
            }
            packagingForm.reset();
            showPrototypeToast('Add Package Group is available as a UI preview only.');
        });
    }

    const viewFinalProductModal = document.getElementById('viewFinalProductModal');

    if (viewFinalProductModal) {
        viewFinalProductModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const detailValues = {
                id: trigger.dataset.productId,
                lotNumber: trigger.dataset.productLotNumber,
                grade: trigger.dataset.productGrade,
                quantity: trigger.dataset.productQuantity,
                dateOfCompletion: trigger.dataset.productDateOfCompletion,
                sourceInspectionId: trigger.dataset.productSourceInspectionId,
                status: trigger.dataset.productStatus
            };

            Object.keys(detailValues).forEach(function (key) {
                const detailElement = viewFinalProductModal.querySelector('[data-product-detail="' + key + '"]');

                if (detailElement) {
                    detailElement.textContent = detailValues[key] || '—';
                }
            });

            const statusElement = viewFinalProductModal.querySelector('[data-product-detail="status"]');

            if (statusElement) {
                statusElement.className = 'status-badge status-badge--' + (trigger.dataset.productStatusClass || 'muted');
            }
        });
    }

    const finalProductsForm = document.querySelector('[data-final-products-form]');

    if (finalProductsForm) {
        finalProductsForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const addFinalProductModal = document.getElementById('addFinalProductModal');
            if (addFinalProductModal && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(addFinalProductModal).hide();
            }
            finalProductsForm.reset();
            showPrototypeToast('Add Product Lot is available as a UI preview only.');
        });
    }

    const viewSupplierModal = document.getElementById('viewSupplierModal');

    if (viewSupplierModal) {
        viewSupplierModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const detailValues = {
                id: trigger.dataset.supplierId,
                name: trigger.dataset.supplierName,
                initials: trigger.dataset.supplierInitials,
                contact: trigger.dataset.supplierContact,
                email: trigger.dataset.supplierEmail,
                address: trigger.dataset.supplierAddress,
                material: trigger.dataset.supplierMaterial,
                materialType: trigger.dataset.supplierMaterialType,
                bom: trigger.dataset.supplierBom,
                quantity: trigger.dataset.supplierQuantity,
                timeRequired: trigger.dataset.supplierTimeRequired
            };

            Object.keys(detailValues).forEach(function (key) {
                viewSupplierModal.querySelectorAll('[data-supplier-detail="' + key + '"]').forEach(function (detailElement) {
                    detailElement.textContent = detailValues[key] || '—';
                });
            });
        });
    }

    const suppliersForm = document.querySelector('[data-suppliers-form]');

    if (suppliersForm) {
        suppliersForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const addSupplierModal = document.getElementById('addSupplierModal');

            if (addSupplierModal && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(addSupplierModal).hide();
            }

            suppliersForm.reset();
            showPrototypeToast('Add Supplier is available as a UI preview only.');
        });
    }

    const viewWorkerModal = document.getElementById('viewWorkerModal');

    if (viewWorkerModal) {
        viewWorkerModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const detailValues = {
                id: trigger.dataset.workerId,
                name: trigger.dataset.workerName,
                grade: trigger.dataset.workerGrade,
                assignedStage: trigger.dataset.workerAssignedStage,
                salary: trigger.dataset.workerSalary,
                status: trigger.dataset.workerStatus,
                address: trigger.dataset.workerAddress,
                email: trigger.dataset.workerEmail,
                contact: trigger.dataset.workerContact,
                lastLogin: trigger.dataset.workerLastLogin
            };

            Object.keys(detailValues).forEach(function (key) {
                const detailElement = viewWorkerModal.querySelector('[data-worker-detail="' + key + '"]');

                if (detailElement) {
                    detailElement.textContent = detailValues[key] || '—';
                }
            });

            const statusElement = viewWorkerModal.querySelector('[data-worker-detail="status"]');

            if (statusElement) {
                statusElement.className = 'status-badge status-badge--' + (trigger.dataset.workerStatusClass || 'muted');
            }
        });
    }

    const workersForm = document.querySelector('[data-workers-form]');

    if (workersForm) {
        workersForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const addWorkerModal = document.getElementById('addWorkerModal');
            if (addWorkerModal && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(addWorkerModal).hide();
            }
            workersForm.reset();
            showPrototypeToast('Add Worker is available as a UI preview only.');
        });
    }

    const viewProductionStageModal = document.getElementById('viewProductionStageModal');

    if (viewProductionStageModal) {
        viewProductionStageModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const detailValues = {
                id: trigger.dataset.stageId,
                name: trigger.dataset.stageName,
                progress: trigger.dataset.stageProgress,
                assignedWorkers: trigger.dataset.stageAssignedWorkers,
                startDate: trigger.dataset.stageStartDate,
                endDate: trigger.dataset.stageEndDate,
                status: trigger.dataset.stageStatus,
                incharge: trigger.dataset.stageIncharge,
                machine: trigger.dataset.stageMachine
            };

            Object.keys(detailValues).forEach(function (key) {
                const detailElement = viewProductionStageModal.querySelector('[data-stage-detail="' + key + '"]');

                if (detailElement) {
                    detailElement.textContent = detailValues[key] || '—';
                }
            });

            const statusElement = viewProductionStageModal.querySelector('[data-stage-detail="status"]');

            if (statusElement) {
                statusElement.className = 'status-badge status-badge--' + (trigger.dataset.stageStatusClass || 'muted');
            }
        });
    }

    const productionStageForm = document.querySelector('[data-production-stage-form]');

    if (productionStageForm) {
        productionStageForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const addProductionStageModal = document.getElementById('addProductionStageModal');

            if (addProductionStageModal && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(addProductionStageModal).hide();
            }

            productionStageForm.reset();
            showPrototypeToast('Add Production Stage is available as a UI preview only.');
        });
    }

    const viewInspectionModal = document.getElementById('viewInspectionModal');

    if (viewInspectionModal) {
        viewInspectionModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const detailValues = {
                id: trigger.dataset.inspectionId,
                stage: trigger.dataset.inspectionStage,
                passed: trigger.dataset.inspectionPassed,
                failed: trigger.dataset.inspectionFailed,
                remarks: trigger.dataset.inspectionRemarks,
                status: trigger.dataset.inspectionStatus,
                date: trigger.dataset.inspectionDate
            };

            Object.keys(detailValues).forEach(function (key) {
                const detailElement = viewInspectionModal.querySelector('[data-inspection-detail="' + key + '"]');

                if (detailElement) {
                    detailElement.textContent = detailValues[key] || '—';
                }
            });

            const statusElement = viewInspectionModal.querySelector('[data-inspection-detail="status"]');

            if (statusElement) {
                statusElement.className = 'status-badge status-badge--' + (trigger.dataset.inspectionStatusClass || 'muted');
            }
        });
    }

    const inspectionsForm = document.querySelector('[data-inspections-form]');

    if (inspectionsForm) {
        inspectionsForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const addInspectionModal = document.getElementById('addInspectionModal');
            if (addInspectionModal && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(addInspectionModal).hide();
            }
            inspectionsForm.reset();
            showPrototypeToast('Add Inspection is available as a UI preview only.');
        });
    }

    const viewInchargeModal = document.getElementById('viewInchargeModal');

    if (viewInchargeModal) {
        viewInchargeModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const detailValues = {
                id: trigger.dataset.inchargeId,
                name: trigger.dataset.inchargeName,
                operatingStage: trigger.dataset.inchargeOperatingStage,
                salary: trigger.dataset.inchargeSalary,
                status: trigger.dataset.inchargeStatus,
                address: trigger.dataset.inchargeAddress,
                email: trigger.dataset.inchargeEmail,
                contact: trigger.dataset.inchargeContact,
                lastLogin: trigger.dataset.inchargeLastLogin
            };

            Object.keys(detailValues).forEach(function (key) {
                const detailElement = viewInchargeModal.querySelector('[data-incharge-detail="' + key + '"]');

                if (detailElement) {
                    detailElement.textContent = detailValues[key] || '—';
                }
            });

            const statusElement = viewInchargeModal.querySelector('[data-incharge-detail="status"]');

            if (statusElement) {
                statusElement.className = 'status-badge status-badge--' + (trigger.dataset.inchargeStatusClass || 'muted');
            }
        });
    }

    const inchargesForm = document.querySelector('[data-incharges-form]');

    if (inchargesForm) {
        inchargesForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const addInchargeModal = document.getElementById('addInchargeModal');
            if (addInchargeModal && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(addInchargeModal).hide();
            }
            inchargesForm.reset();
            showPrototypeToast('Add Incharge is available as a UI preview only.');
        });
    }

    const viewMachineryModal = document.getElementById('viewMachineryModal');

    if (viewMachineryModal) {
        viewMachineryModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const detailValues = {
                id: trigger.dataset.machineId,
                name: trigger.dataset.machineName,
                type: trigger.dataset.machineType,
                costPerUnit: trigger.dataset.machineCostPerUnit,
                quantity: trigger.dataset.machineQuantity,
                status: trigger.dataset.machineStatus,
                currentStage: trigger.dataset.machineCurrentStage,
                usedDuration: trigger.dataset.machineUsedDuration,
                usedCost: trigger.dataset.machineUsedCost
            };

            Object.keys(detailValues).forEach(function (key) {
                const detailElement = viewMachineryModal.querySelector('[data-machine-detail="' + key + '"]');

                if (detailElement) {
                    detailElement.textContent = detailValues[key] || '—';
                }
            });

            const statusElement = viewMachineryModal.querySelector('[data-machine-detail="status"]');

            if (statusElement) {
                statusElement.className = 'status-badge status-badge--' + (trigger.dataset.machineStatusClass || 'muted');
            }
        });
    }

    const machineryForm = document.querySelector('[data-machinery-form]');

    if (machineryForm) {
        machineryForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const addMachineryModal = document.getElementById('addMachineryModal');
            if (addMachineryModal && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(addMachineryModal).hide();
            }
            machineryForm.reset();
            showPrototypeToast('Add Machine is available as a UI preview only.');
        });
    }

    const viewMaterialModal = document.getElementById('viewMaterialModal');

    if (viewMaterialModal) {
        viewMaterialModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const detailValues = {
                id: trigger.dataset.materialId,
                name: trigger.dataset.materialName,
                type: trigger.dataset.materialType,
                unitOfMeasure: trigger.dataset.materialUnitOfMeasure,
                unitPrice: trigger.dataset.materialUnitPrice,
                supplier: trigger.dataset.materialSupplier
            };

            Object.keys(detailValues).forEach(function (key) {
                const detailElement = viewMaterialModal.querySelector('[data-material-detail="' + key + '"]');

                if (detailElement) {
                    detailElement.textContent = detailValues[key] || '—';
                }
            });
        });
    }

    const materialsForm = document.querySelector('[data-materials-form]');

    if (materialsForm) {
        materialsForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const addMaterialModal = document.getElementById('addMaterialModal');

            if (addMaterialModal && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(addMaterialModal).hide();
            }

            materialsForm.reset();
            showPrototypeToast('Add Material is available as a UI preview only.');
        });
    }

    const viewBomModal = document.getElementById('viewBomModal');

    if (viewBomModal) {
        viewBomModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const detailValues = {
                id: trigger.dataset.bomId,
                description: trigger.dataset.bomDescription,
                unitBill: trigger.dataset.bomUnitBill,
                totalBill: trigger.dataset.bomTotalBill,
                materialsBreakdown: trigger.dataset.bomMaterialsBreakdown, // New: JSON string
                status: trigger.dataset.bomStatus
            };

            Object.keys(detailValues).forEach(function (key) {
                const detailElement = viewBomModal.querySelector('[data-bom-detail="' + key + '"]');

                if (detailElement) {
                    detailElement.textContent = detailValues[key] || '—';
                }
            });

            // Handle materials breakdown for ternary relationship
            const materialsBreakdownContainer = viewBomModal.querySelector('[data-bom-detail="materialsBreakdown"]');
            if (materialsBreakdownContainer && detailValues.materialsBreakdown) {
                materialsBreakdownContainer.innerHTML = ''; // Clear previous content
                try {
                    const breakdown = JSON.parse(detailValues.materialsBreakdown);
                    breakdown.forEach(item => {
                        const div = document.createElement('div');
                        div.classList.add('bom-material-item');
                        div.innerHTML = `
                            <strong>${item.materialName}</strong>
                            <span>from ${item.supplierName}</span>
                            <small>${item.quantity} · ${item.timeRequired}</small>
                        `;
                        materialsBreakdownContainer.appendChild(div);
                    });
                } catch (e) {
                    console.error('Error parsing BOM materials breakdown JSON:', e);
                    materialsBreakdownContainer.textContent = 'Error loading details.';
                }
            } else if (materialsBreakdownContainer) {
                materialsBreakdownContainer.textContent = 'No material breakdown available.';
            });

            const statusElement = viewBomModal.querySelector('[data-bom-detail="status"]');

            if (statusElement) {
                statusElement.className = 'status-badge status-badge--' + (trigger.dataset.bomStatusClass || 'muted');
            }
        });
    }

    const bomForm = document.querySelector('[data-bom-form]');

    if (bomForm) {
        bomForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const addBomModal = document.getElementById('addBomModal');
            if (addBomModal && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(addBomModal).hide();
            }
            bomForm.reset();
            showPrototypeToast('Add BOM is available as a UI preview only.');
        });
    }

    const viewTransactionModal = document.getElementById('viewTransactionModal');

    if (viewTransactionModal) {
        viewTransactionModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const detailValues = {
                id: trigger.dataset.transactionId,
                date: trigger.dataset.transactionDate,
                description: trigger.dataset.transactionDescription,
                status: trigger.dataset.transactionStatus,
                amount: trigger.dataset.transactionAmount,
                bank: trigger.dataset.transactionBank,
                source: trigger.dataset.transactionSource
            };

            Object.keys(detailValues).forEach(function (key) {
                const detailElement = viewTransactionModal.querySelector('[data-transaction-detail="' + key + '"]');

                if (detailElement) {
                    detailElement.textContent = detailValues[key] || '—';
                }
            });

            const statusElement = viewTransactionModal.querySelector('[data-transaction-detail="status"]');

            if (statusElement) {
                statusElement.className = 'status-badge status-badge--' + (trigger.dataset.transactionStatusClass || 'muted');
            }
        });
    }

    const accountsForm = document.querySelector('[data-accounts-form]');

    if (accountsForm) {
        accountsForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const addTransactionModal = document.getElementById('addTransactionModal');
            if (addTransactionModal && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(addTransactionModal).hide();
            }
            accountsForm.reset();
            showPrototypeToast('Add Transaction is available as a UI preview only.');
        });
    }

    const viewPaymentModal = document.getElementById('viewPaymentModal');

    if (viewPaymentModal) {
        viewPaymentModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const detailValues = {
                id: trigger.dataset.paymentId,
                orderId: trigger.dataset.paymentOrderId,
                buyerName: trigger.dataset.paymentBuyerName,
                totalAmount: trigger.dataset.paymentTotalAmount,
                paidAmount: trigger.dataset.paymentPaidAmount,
                remainingAmount: trigger.dataset.paymentRemainingAmount,
                method: trigger.dataset.paymentMethod,
                date: trigger.dataset.paymentDate,
                status: trigger.dataset.paymentStatus
            };

            Object.keys(detailValues).forEach(function (key) {
                const detailElement = viewPaymentModal.querySelector('[data-payment-detail="' + key + '"]');

                if (detailElement) {
                    detailElement.textContent = detailValues[key] || '—';
                }
            });

            const statusElement = viewPaymentModal.querySelector('[data-payment-detail="status"]');

            if (statusElement) {
                statusElement.className = 'status-badge status-badge--' + (trigger.dataset.paymentStatusClass || 'muted');
            }
        });
    }

    const paymentsForm = document.querySelector('[data-payments-form]');

    if (paymentsForm) {
        paymentsForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const addPaymentModal = document.getElementById('addPaymentModal');
            if (addPaymentModal && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(addPaymentModal).hide();
            }
            paymentsForm.reset();
            showPrototypeToast('Add Payment is available as a UI preview only.');
        });
    }

    /* --- Shared prototype action listener --- */
    document.querySelectorAll('[data-prototype-action]').forEach(function (button) {
        button.addEventListener('click', function () {
            showPrototypeToast(button.dataset.prototypeAction + ' is available as a UI preview only.');
        });
    });
});
