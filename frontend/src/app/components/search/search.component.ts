import { Component, EventEmitter, OnInit, Output } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule } from '@angular/forms';

@Component({
  selector: 'app-search',
  standalone: true,
  imports: [ ReactiveFormsModule],
  templateUrl: './search.component.html',
  styleUrl: './search.component.css'
})
export class SearchComponent  implements OnInit{

  searchFrom!: FormGroup;
  @Output() onSearch = new EventEmitter<any>();

  constructor( private fb: FormBuilder) {}

  ngOnInit(): void {
    this.inItSearchForm();
  }

  inItSearchForm() {
    this.searchFrom = this.fb.group({
      city: ['']
    });
  }

  onSubmit(){
    this.onSearch.emit(this.searchFrom.value.city);
  }
}
