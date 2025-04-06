import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EventDetailsEditorComponent } from './event-details-editor.component';

describe('EventDetailsEditorComponent', () => {
  let component: EventDetailsEditorComponent;
  let fixture: ComponentFixture<EventDetailsEditorComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [EventDetailsEditorComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(EventDetailsEditorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
