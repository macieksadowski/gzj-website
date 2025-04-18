import { Directive, ElementRef, HostListener, Renderer2 } from '@angular/core';

@Directive({
    selector: '[currencyFormat]'
})
export class CurrencyFormatDirective {
    private locale: string = 'pl-PL';
    private currency: string = 'PLN';

    constructor(private el: ElementRef, private renderer: Renderer2) {}

    ngOnInit() {
        this.formatValue();
    }

    @HostListener('input', ['$event'])
    onInput(event: any) {
        console.log('onInput', event.target.value);
        let value = event.target.value.replace(/[^\d,.-]/g, '');
        console.log('onInput after replace', value);
        value = value.replace(',', '.');

        if (value !== '-') {
          console.log('condition', value);
          const numberValue = parseFloat(value) || '';
          this.renderer.setProperty(this.el.nativeElement, 'value', numberValue);
        }
    }
    
      @HostListener('blur')
      onBlur() {
        this.formatValue();
      }

      private formatValue() {
        const value = parseFloat(this.el.nativeElement.value) || 0;
        const formatted = new Intl.NumberFormat(this.locale, {
          style: 'currency',
          currency: this.currency
        }).format(value);
        
        this.renderer.setProperty(this.el.nativeElement, 'value', formatted);
      }
    }