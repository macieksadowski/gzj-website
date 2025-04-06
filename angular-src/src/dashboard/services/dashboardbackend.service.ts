import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Event } from '../model/event';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class DashboardBackendService {
  private apiUrl = environment.apiUrl;
  private apiToken = environment.apiToken;

  private getHeaders() {
    return {
      headers: new HttpHeaders({
        'Authorization': `Bearer ${this.apiToken}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }),
      withCredentials: true
    };
  }
  

  constructor(private http: HttpClient) { }


  /** EVENTS */

  getAllEvents(): Observable<any[]> {
    console.log('Fetching events from API');
    return this.http.get<any[]>(`${this.apiUrl}/events`, this.getHeaders());
  }

  getEventById(eventId: string | null) {
    console.log(`Fetching event with id ${eventId} from API`);
    return this.http.get<any>(`${this.apiUrl}/events/${eventId}`, this.getHeaders());
  }

  getEventSuggestions(query: string): Observable<any[]> {
    console.log(`Fetching event suggestions from API`);
    return this.http.get<any[]>(`${this.apiUrl}/events-search?search=${query}`, this.getHeaders());
  }

  deleteEvent(eventId: string) {
    console.log(`Deleting event with id ${eventId}`);
    return this.http.delete(`${this.apiUrl}/events/${eventId}`, this.getHeaders());
  }

  createEvent(event: Event): Observable<Event> {
    console.log(`Creating event with name ${event.name}`);
    return this.http.post<Event>(`${this.apiUrl}/events/new`, this.convertEventForBackend(event), this.getHeaders());
  }

  updateEvent(event: Event): Observable<Event> {
    console.log(`Updating event with id ${event.id}`);
    return this.http.post<Event>(`${this.apiUrl}/events/${event.id}/edit`, this.convertEventForBackend(event), this.getHeaders());
  }

  getEventTypes(): Observable<any[]> {
    console.log(`Fetching event types from API`);
    return this.http.get<any[]>(`${this.apiUrl}/event-types`, this.getHeaders());
  }

  convertEventForBackend(event: Event) {
    return { ...event, type: event.type.id };
  }

  convertTransactionForBackend(transaction: any) {
    return transaction;
  }

  /** CONTRACTS */

  getAllContracts(): Observable<any[]> {
    console.log('Fetching contracts from API');
    return this.http.get<any[]>(`${this.apiUrl}/contracts`, this.getHeaders());
  }

  getContractsSummaryPerYear(): Observable<any[]> {
    console.log('Fetching contracts summary per year from API');
    return this.http.get<any[]>(`${this.apiUrl}/contracts/summaryPerYear`, this.getHeaders());
  }

  /** MEMBERS */

  getAllMembersNames(): Observable<any[]> {
    console.log(`Fetching members names from API`);
    return this.http.get<any[]>(`${this.apiUrl}/members/names`, this.getHeaders());
  }

  getAllMembers(): Observable<any[]> {
    console.log(`Fetching all members from API`);
    return this.http.get<any[]>(`${this.apiUrl}/members`, this.getHeaders());
  }

  createMember(member: any): Observable<any> {
    console.log(`Creating member with name ${member.first_name} ${member.last_name}`);
    return this.http.post<any>(`${this.apiUrl}/members/new`, member, this.getHeaders());
  }

  editMember(member: any): Observable<any> {
    console.log(`Editing member with id ${member.id}`);
    return this.http.post<any>(`${this.apiUrl}/members/${member.id}/edit`, member, this.getHeaders());
  }

  /** FINANCES */

  getTransactions(): Observable<any[]> {
    console.log(`Fetching transactions from API`);
    return this.http.get<any[]>(`${this.apiUrl}/transactions`, this.getHeaders());
  }

  getSaldo(): Observable<any> {
    console.log(`Fetching saldo from API`);
    return this.http.get<any>(`${this.apiUrl}/transactions-saldo`, this.getHeaders());
  }

  deleteTransaction(transactionId: Number) : Observable<any> {
    console.log(`Deleting transaction with id ${transactionId}`);
    return this.http.delete<string>(`${this.apiUrl}/transactions/${transactionId}`, this.getHeaders());
  }

  createTransaction(transaction: any): Observable<any> {
    console.log(`Creating transaction with description ${transaction.description}`);
    return this.http.post<any>(`${this.apiUrl}/transactions/new`, transaction, this.getHeaders());
  }

  updateTransaction(transaction: any): Observable<any> {
    console.log(`Updating transaction with id ${transaction.tr_id}`);
    return this.http.post<Event>(`${this.apiUrl}/transactions/${transaction.tr_id}/edit`, this.convertTransactionForBackend(transaction), this.getHeaders());
  }

  getTransactionCategories(): Observable<any[]> {
    console.log(`Fetching transaction categories from API`);
    return this.http.get<any[]>(`${this.apiUrl}/transaction-categories`, this.getHeaders());
  }

  getSongs(): Observable<any[]> {
    console.log(`Fetching songs from API`);
    return this.http.get<any[]>(`${this.apiUrl}/songs`, this.getHeaders());
  }

  deleteSong(songId: Number) : Observable<any> {
    console.log(`Deleting song with id ${songId}`);
    return this.http.delete<string>(`${this.apiUrl}/songs/${songId}`, this.getHeaders());
  }

  createSong(song: any): Observable<any> {
    console.log(`Creating song with title ${song.title}`);
    return this.http.post<any>(`${this.apiUrl}/songs/new`, song, this.getHeaders());
  }

  updateSong(song: any): Observable<any> {
    console.log(`Updating song with id ${song.id}`);
    return this.http.post<any>(`${this.apiUrl}/songs/${song.id}/edit`, song, this.getHeaders());
  }


  generateZaiksReport(eventName: string, songs: string[]): Observable<any> {
    console.log(`Generating ZAIKS report for event ${eventName}`);
    return this.http.post(`${this.apiUrl}/zaiks/generate`, { eventName, songs }, {
      headers: this.getHeaders().headers, 
      withCredentials: this.getHeaders().withCredentials, 
      responseType: 'blob',
      observe: 'response'
    });
  }

  generateContract(fileName: string, contractType: string, member_id: number): Observable<any> {
    console.log(`Generating contract ${fileName} of type ${contractType}`);
    return this.http.post(`${this.apiUrl}/contracts/generate`, { fileName, contractType, member_id}, {
      headers: this.getHeaders().headers, 
      withCredentials: this.getHeaders().withCredentials, 
      responseType: 'blob',
      observe: 'response'
    });
  }

  getFileNameFromContentDisposition(contentDisposition: string | null): string {
    const matches = /filename=(.+)/.exec(contentDisposition || '');
    return matches && matches[1] ? matches[1] : 'ZAiKS_Report.docx';
  }

  downloadFile(response: Blob, fileName: string): void {
    const link = document.createElement('a');
    const url = window.URL.createObjectURL(response);
    link.href = url;
    link.download = fileName;
    link.click();
  }

  

}
