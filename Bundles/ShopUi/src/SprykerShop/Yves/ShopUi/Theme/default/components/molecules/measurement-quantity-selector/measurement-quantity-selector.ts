import Component from '../../../models/component';

export default class MeasurementQuantitySelector extends Component {
    qtyInput: HTMLElement;
    measurementUnitInput: HTMLElement;
    addToCartButton: HTMLElement;

    baseUnit: any;
    salesUnits: any;
    currentSalesUnit: any;
    productQuantityStorage: any;
    currentValue: Number;


    readyCallback(event?: Event): void {
        this.qtyInput = document.querySelector('.select-quantity');
        this.measurementUnitInput = document.querySelector('.select-measurement-unit');
        this.addToCartButton = document.getElementById('add-to-cart-button');

        this.initJson();
        this.initCurrentSalesUnit();
        this.mapEvents();
    }

    initJson() {
        let jsonSchemaContainer = document.getElementsByClassName(this.name + '__json')[0];
        if (jsonSchemaContainer.hasAttribute('json')) {
            let jsonString = jsonSchemaContainer.getAttribute('json');
            let jsonData = JSON.parse(jsonString);

            if (jsonData.hasOwnProperty('baseUnit')) {
                this.baseUnit = jsonData.baseUnit;
            }

            if (jsonData.hasOwnProperty('salesUnits')) {
                this.salesUnits = jsonData.salesUnits;
            }

            if (jsonData.hasOwnProperty('productQuantityStorage')) {
                this.productQuantityStorage = jsonData.productQuantityStorage;
            }
        }
    }

    initCurrentSalesUnit() {
        for (let key in this.salesUnits) {
            if (this.salesUnits.hasOwnProperty(key)) {
                if (this.salesUnits[key].is_default) {
                    this.currentSalesUnit = this.salesUnits[key];
                }
            }
        }
    }

    mapEvents() {
        this.qtyInput.addEventListener('change', (event: Event) => this.qtyInputChange());
        this.measurementUnitInput.addEventListener('change', (event: Event) => this.measurementUnitInputChange(event));
    }

    qtyInputChange() {
        let userQty = (this.qtyInput as HTMLInputElement).value;
        let qtyInBaseUnits = +userQty * +this.currentSalesUnit.conversion;
        if (qtyInBaseUnits % 1 != 0) {
            this.addToCartButton.setAttribute("disabled", "disabled");
            this.askCustomerForCorrectInput();
            return;
        }

        this.addToCartButton.removeAttribute("disabled");
        document.querySelector('.measurement-unit-choice').classList.add('is-hidden');
        return;
    }

    askCustomerForCorrectInput() {
        let userQty = (this.qtyInput as HTMLInputElement).value;
        let qtyInBaseUnits = +userQty * +this.currentSalesUnit.conversion;
        let choicesList = document.querySelector('#measurement-unit-choices .list');
        let currentChoice = document.querySelector('.measurement-unit-choice #current-choice');
        let maxChoice = this.getMaxChoice(qtyInBaseUnits);
        let minChoice = this.getMinChoice(qtyInBaseUnits);
        choicesList.innerHTML = '';
        currentChoice.innerHTML = '';
        currentChoice.textContent = `${userQty} ${this.currentSalesUnit.product_measurement_unit.codeg}`;

        [minChoice, maxChoice]
            .filter((v, i, a) => a.indexOf(v) === i)
            .forEach(function (choice) {
                let elem = this.createChoiceElement(choice);
                if (elem !== null) {
                    choicesList.appendChild(elem);
                }

            }.bind(this));

        document.querySelector('.measurement-unit-choice').classList.remove('is-hidden');
    }

    createChoiceElement(choice: number) {
        if (choice > 0) {
            let choiceElem = document.createElement('span');
            let valueInSalesUnits = choice / this.currentSalesUnit.conversion;
            let measurementSalesUnitCode = this.currentSalesUnit.product_measurement_unit.code;
            let valueInBaseUnits = valueInSalesUnits * this.currentSalesUnit.conversion;
            let measurementBaseUnitCode = this.baseUnit.code;
            choiceElem.classList.add('link');
            choiceElem.setAttribute('data-qty', valueInSalesUnits.toString());
            choiceElem.textContent = `(${valueInSalesUnits} ${measurementSalesUnitCode}) = (${valueInBaseUnits} ${measurementBaseUnitCode})`;
            choiceElem.onclick = function (event: Event) {
                let elem = event.srcElement as HTMLElement;
                this.selectQty(parseInt(elem.dataset.qty));
            }.bind(this);

            choiceElem.style.display = 'block';

            return choiceElem;
        }

        return null;
    }

    selectQty(qty) {
        let qtyInput = document.querySelector('.select-quantity') as HTMLSelectElement;

        qtyInput.value = qty.toString();
        this.addToCartButton.removeAttribute("disabled");
        document.querySelector('.measurement-unit-choice').classList.add('is-hidden');
        this.qtyInputChange()
    }

    getMinChoice(qtyInBaseUnits: number) {
        let minChoice = Math.floor(qtyInBaseUnits);

        if (minChoice >= this.getMinQuantity()) {
            return minChoice;
        }

        return this.getMinQuantity();
    }

    getMaxChoice(qtyInBaseUnits: number) {
        let maxChoice = Math.ceil(qtyInBaseUnits);

        if (this.getMaxQuantity() > 0 && maxChoice >= this.getMaxQuantity()) {
            return maxChoice;
        }

        return this.getMinQuantity();
    }

    getMinQuantity() {
        if (this.productQuantityStorage.hasOwnProperty('quantity_min')) {
            return this.productQuantityStorage.quantity_min;
        }

        return 1;
    }

    getMaxQuantity() {
        if (this.productQuantityStorage.hasOwnProperty('quantity_max')) {
            return this.productQuantityStorage.quantity_max;
        }

        return 0;
    }

    measurementUnitInputChange(event: Event) {
        let salesUnitId = parseInt((event.srcElement as HTMLSelectElement).value);
        let salesUnit = this.getSalesUnitById(salesUnitId);
        let userQty = (this.qtyInput as HTMLInputElement).value;
        let qtyInBaseUnits = +userQty * +this.currentSalesUnit.conversion;
        let qtyInSalesUnits = +qtyInBaseUnits / +salesUnit.conversion;
        this.currentSalesUnit = salesUnit;
        (this.qtyInput as HTMLInputElement).value = qtyInSalesUnits.toString();
    }


    getSalesUnitById(salesUnitId: number) {
        for (let key in this.salesUnits) {
            if (this.salesUnits.hasOwnProperty(key)) {
                if (salesUnitId == this.salesUnits[key].id_product_measurement_sales_unit) {
                    return this.salesUnits[key];
                }
            }
        }
    }

    getBaseSalesUnit() {
        for (let key in this.salesUnits) {
            if (this.salesUnits.hasOwnProperty(key)) {
                if (this.baseUnit.id_product_measurement_unit == this.salesUnits[key].product_measurement_unit.id_product_measurement_unit) {
                    return this.salesUnits[key];
                }
            }
        }
    }
}
