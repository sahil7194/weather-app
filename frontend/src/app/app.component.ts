import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterOutlet } from '@angular/router';
import { SearchComponent } from './components/search/search.component';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { Weather } from './models/Weather';
import {MatIconModule} from '@angular/material/icon'
import { Title } from '@angular/platform-browser';


@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, RouterOutlet, SearchComponent, HttpClientModule , MatIconModule],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent implements OnInit {

  title: string = 'Weather ';
  url: string = 'http://127.0.0.1:8000/api/weather?city=';
  data!: any;
  cityName:string = 'Delhi';

  constructor(private httpClient: HttpClient , private titleService: Title) { }

  ngOnInit(): void {

    this.fetchWeatherData();
    this.titleService.setTitle(this.title);

  }

  fetchWeatherData() {

    this.httpClient.get(this.url + this.cityName ).subscribe({
      next: (response: any) => {
        this.data = response.data
        this.titleService.setTitle(this.title +" "+ this.data.name );
      },
    })

  }

  onSearchEmit(value: string): void {

    this.cityName = value;

    this.fetchWeatherData();

  }


}
