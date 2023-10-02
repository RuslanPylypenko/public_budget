import styled from 'styled-components';

export const Container = styled.div`
  width: 100%;
  max-width: 1800px;
  padding-right: 15px;
  padding-left: 15px;
  margin-right: auto;
  margin-left: auto;
  @media (max-width: 1921px){
    max-width: 1434px;
  }
  @media (max-width: 1493px){
    max-width: 1140px;
  }
  @media (max-width: 1199px){
    max-width: 962px;
  }
  @media (max-width: 991px){
    max-width: 738px;
  }
  @media (max-width: 767px){
    max-width: 100%;
    padding-right: 10px;
    padding-left: 10px;
  }
`;